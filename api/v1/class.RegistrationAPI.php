<?php
class RegistrationAPI extends Http\Rest\DataTableAPI
{
    protected $adminType;

    public function __construct($dataTable, $adminType)
    {
        parent::__construct('registration', $dataTable, '_id');
        $this->adminType = $adminType;
    }

    public function setup($app)
    {
        parent::setup($app);
        $app->get('/Actions/Search', array($this, 'searchData'));
        $app->get('/{name}/{field}[/]', array($this, 'readEntryField'));
        $app->post('/{name}[/]', array($this, 'updateEntry'));
        $app->post('/{name}/contact[/{lead}]', array($this, 'contactLead'));
        $app->post('/{name}/Actions/Unlock', array($this, 'unlockEntry'));
    }

    protected function processEntry($obj, $request)
    {
        $strip = (!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType));
        foreach($obj as $key=>$value)
        {
            if($key == '_id')
            {
                $obj['_id'] = (string)$obj['_id'];
            }
            else if(is_object($value) || is_array($value))
            {
                if($key === 'value') continue;
                if($strip)
                {
                    unset($obj[$key]);
                }
            }
        }
        return $obj;
    }

    protected function canCreate($request)
    {
        $this->validateLoggedIn($request);
        return true;
    }

    protected function canUpdate($request, $entity)
    {
        $this->validateLoggedIn($request);
        if($this->user->isInGroupNamed('RegistrationAdmins') || $this->user->isInGroupNamed($this->adminType))
        {
            return true;
        }
        if(!isset($entity['registrars']) || $entity['registrars'] == null)
        {
            error_log('Missing registrars! '.print_r($entity, true));
            return false;
        }
        return in_array($this->user->uid, $entity['registrars']);
    }

    protected function canDelete($request, $entity)
    {
        $this->validateLoggedIn($request);
        if($this->user->isInGroupNamed('RegistrationAdmins') || $this->user->isInGroupNamed($this->adminType))
        {
            return true;
        }
        return in_array($this->user->uid, $entity['registrars']);
    }

    protected function getFilterForPrimaryKey($value)
    {
        //Ensure the polyfill is loaded if needed
        $this->getDataTable();
        if(class_exists('MongoId'))
        {
            return new \Data\Filter($this->primaryKeyName.' eq '.new \MongoId($value));
        }
        else
        {
            return new \Data\Filter($this->primaryKeyName.' eq '.new \MongoDB\BSON\ObjectId($value));
        }
    }

    protected function getCurrentYear()
    {
        $varsDataTable = \DataSetFactory::getDataTableByNames($this->dataSetName, 'vars');
        $arr = $varsDataTable->read(new \Data\Filter("name eq 'year'"));
        return intval($arr[0]['value']);
    }

    protected function manipulateParameters($request, &$odata)
    {
        $queryParams = $request->getQueryParams();
        if((!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType)) ||
           $odata->filter === false)
        {
            $odata->filter = new \Data\Filter('year eq '.$this->getCurrentYear());
        }
        else if($odata->filter !== false && $odata->filter->contains('year eq current'))
        {
            $clause = $odata->filter->getClause('year');
            $clause->var2 = $this->getCurrentYear();
        }
        if(isset($queryParams['no_logo']))
        {
            return array('fields'=>array('logo' => false, 'image_1' => false, 'image_2' => false, 'image_3' => false, 'image' => false));
        }
        return false;
    }

    protected function validateCreate(&$obj, $request)
    {
        if(!isset($obj['name']) || !isset($obj['teaser']) || !isset($obj['description']))
        {
            throw new Exception('Missing one or more required parameters!', \Http\Rest\INTERNAL_ERROR);
        }
        $obj['year'] = $this->getCurrentYear();
        if(!isset($obj['registrars']))
        {
            $obj['registrars'] = array();
        }
        if(!in_array($this->user->uid, $obj['registrars']))
        {
            array_push($obj['registrars'], $this->user->uid);
        }
        return true;
    }

    protected function validateUpdate(&$newObj, $request, $oldObj)
    {
        if(!isset($newObj['name']) || !isset($newObj['teaser']) || !isset($newObj['description']))
        {
            throw new Exception('Missing one or more required parameters!', \Http\Rest\INTERNAL_ERROR);
        }
        $newObj['year'] = $this->getCurrentYear();
        if(!isset($newObj['registrars']))
        {
            $newObj['registrars'] = array();
        }
        if(!isset($oldObj['registrars']))
        {
            $oldObj['registrars'] = array();
        }
        $newObj['registrars'] = array_merge($newObj['registrars'], $oldObj['registrars']);
        if($this->user->isInGroupNamed('RegistrationAdmins') || $this->user->isInGroupNamed($this->adminType))
        {
            array_push($newObj['registrars'], $this->user->uid);
        }
        $newObj['registrars'] = array_unique($newObj['registrars']);
        if(!isset($newObj['_id']))
        {
             $newObj['_id'] = (string)$oldObj['_id'];
        }
    }

    public function readEntry($request, $response, $args)
    {
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        $dataTable = $this->getDataTable();
        $odata = $request->getAttribute('odata', new \ODataParams(array()));
        $filter = $this->getFilterForPrimaryKey($args['name']);
        $areas = $dataTable->read($filter, $odata->select, $odata->top,
                                  $odata->skip, $odata->orderby);
        if(empty($areas))
        {
            return $response->withStatus(404);
        }
        $obj = $areas[0];
        $queryParams = $request->getQueryParams();
        if(!isset($queryParams['full']))
        {
            if((!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType)) ||
               !isset($args['field']))
            {
                $obj = $this->processEntry($obj, $request);
            }
            if(isset($args['field']))
            {
                $field = $args['field'];
                $value = $obj[$field];
                if(!is_array($value) && strncmp($value, 'data:', 5) === 0)
                {
                    $str = substr($obj[$field], 5);
                    $type = strtok($str, ';');
                    strtok(',');
                    $str = strtok("\0");
                    $response = $response->withBody(new \Slim\Http\Body(fopen('php://temp', 'r+')));
                    $response->getBody()->write(base64_decode($str));
                    return $response->withHeader('Content-Type', $type);
                }
                return $response->withJson($value);
            }
        }
        else
        {
            if($this->canUpdate($request, $obj) === false)
            {
                throw new Exception('Cannot edit object that is not yours', ACCESS_DENIED);
            }
        }
        return $response->withJson($areas[0]);
    }

    private function processStructs(&$camp)
    {
        $totalSqFt = 0;
        if(isset($camp['structs']['type']))
        {
            $structs = $camp['structs'];
            $count = count($structs['type']);
            for($i = 0; $i < $count; $i++)
            {
                $newStruct = new stdClass();
                $newStruct->type = $structs['type'][$i];
                $newStruct->width = $structs['width'][$i];
                $newStruct->length = $structs['length'][$i];
                $newStruct->height = $structs['height'][$i];
                $newStruct->desc = $structs['desc'][$i];
                $newStruct->sqFt = $newStruct->width*$newStruct->length;
                $totalSqFt += $newStruct->sqFt;
                $camp['structs'][$i] = $newStruct;
            }
            unset($camp['structs']['type']);
            unset($camp['structs']['width']);
            unset($camp['structs']['length']);
            unset($camp['structs']['height']);
            unset($camp['structs']['desc']);
        }
        if(isset($camp['placement']['tents']))
        {
            $tentCount = $camp['placement']['tents'];
            unset($camp['placement']);
            $camp['tents'] = $tentCount;
            $camp['tentSqFt'] = $tentCount*100;
            $totalSqFt += $camp['tentSqFt'];
        }
        $camp['totalSqFt'] = $totalSqFt;
    }

    private function doStructView($request, $response, $args)
    {
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        if(!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType))
        {
            return $response->withStatus(401);
        }
        $dataTable = $this->getDataTable();
        $odata = $request->getAttribute('odata', new \ODataParams(array()));
        if($args['name'] !== '*')
        {
            $odata->filter = $this->getFilterForPrimaryKey($args['name']);
        }
        else if($odata->filter === false)
        {
            $odata->filter = new \Data\Filter('year eq '.$this->getCurrentYear());
        }
        $hideLogo = array('fields'=>array('logo' => false, 'image_1' => false, 'image_2' => false, 'image_3' => false, 'image' => false));
        $objs = $dataTable->read($odata->filter, $odata->select, $odata->top,
                                 $odata->skip, $odata->orderby, $hideLogo);
        if(empty($objs))
        {
            return $response->withStatus(404);
        }
        $res = array();
        $campCount = count($objs);
        for($i = 0; $i < $campCount; $i++)
        {
            $camp = $objs[$i];
            $tmpObj = array('_id'=>$camp['_id'], 'name'=>$camp['name']);
            //First put the regular tents
            $tents = $tmpObj;
            $tents['desc'] = 'Regular Tents 10x10';
            $tents['count'] = $camp['placement']['tents'];
            $tents['sqFt'] = $tents['count']*100;
            array_push($res, $tents);
            if(!isset($camp['structs']))
            {
                continue;
            }
            $structCount = count($camp['structs']['type']);
            for($j = 0; $j < $structCount; $j++)
            {
                $struct = $tmpObj;
                $struct['desc'] = $camp['structs']['desc'][$j];
                $struct['type'] = $camp['structs']['type'][$j];
                $struct['width'] = $camp['structs']['width'][$j];
                $struct['height'] = $camp['structs']['height'][$j];
                $struct['length'] = $camp['structs']['length'][$j];
                $struct['sqFt'] = $struct['width']*$struct['length'];
                array_push($res, $struct);
            }
        }
        return $response->withJson($res);
    }

    public function readEntryField($request, $response, $args)
    {
        if($args['field'] === 'doStructView')
        {
            return $this->doStructView($request, $response, $args);
        }
        if($args['name'] !== '*')
        {
            if($args['field'] === 'structs')
            {
                $dataTable = $this->getDataTable();
                $odata = $request->getAttribute('odata', new \ODataParams(array()));
                $odata->select = array($args['field'], 'placement');
                $odata->filter = $this->getFilterForPrimaryKey($args['name']);
                $objs = $dataTable->read($odata->filter, $odata->select, $odata->top,
                                         $odata->skip, $odata->orderby);
                if(empty($objs))
                {
                    return $response->withStatus(404);
                }
                $objs = $objs[0];
                $this->processStructs($objs);
                return $response->withJson($objs);
            }
            $odata = $request->getAttribute('odata', new \ODataParams(array()));
            $odata->select = array($args['field']);
            $request = $request->withAttribute('odata', $odata);
            return $this->readEntry($request, $response, $args);
        }
        $field = $args['field'];
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        if(!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType))
        {
            return $response->withStatus(401);
        }
        $dataTable = $this->getDataTable();
        $odata = $request->getAttribute('odata', new \ODataParams(array()));
        $objs = $dataTable->read($odata->filter, $odata->select, $odata->top,
                                 $odata->skip, $odata->orderby);
        $res = array();
        $count = count($objs);
        for($i = 0; $i < $count; $i++)
        {
            if($args['field'] === 'structs')
            {
                $this->processStructs($objs[$i]);
            }
            if(isset($objs[$i][$field]))
            {
                array_push($res, $objs[$i][$field]);
            }
        }
        $params = $request->getParams();
        return $response->withJson($res);
    }

    public function searchData($request, $response, $args)
    {
        $params = $request->getParams();
        $dataTable = $this->getDataTable();
        foreach($params as $key=>$value)
        {
            $value = str_replace('"','',$value);
            if($value[0] === '/')
            {
                $params[$key] = array('$regex'=>new MongoRegex("$value"));
            }
        }
        if(!isset($params['year']))
        {
            $params['year'] = $this->getCurrentYear();
        }
        else if($params['year'] === '*')
        {
            unset($params['year']);
        }
        $data = $dataTable->read($params);
        return $response->withJson($data);
    }

    public function contactLead($request, $response, $args)
    {
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        if(!isset($args['name']))
        {
            return $response->withStatus(400);
        }
        $id = $args['name'];
        $lead = false;
        if(isset($args['lead']))
        {
            $lead = $args['lead'];
        }
        else
        {
            if($this->dataTable === 'art')
            {
                $lead = 'artLead';
            }
            else if($this->dataTable === 'camps')
            {
                $lead = 'campLead';
            }
            
        }
        if($lead === false)
        {
            throw new Exception('No default lead for '.$this->dataTable.'!', \Http\Rest\INTERNAL_ERROR);
        }
        $params = $request->getQueryParams();
        if(!isset($params['subject']) || !isset($params['email_text']))
        {
            $params = $request->getParsedBody();
        }
        $dataTable = $this->getDataTable();
        $filter = $this->getFilterForPrimaryKey($args['name']);
        $obj = $dataTable->read($filter);
        if(empty($obj))
        {
            return $response->withStatus(404);
        }
        $lead = $obj[0][$lead];
        $email_msg = new \Email\Email();
        $email_msg->setFromAddress('webmaster@burningflipside.com','Burning Flipside Contact Form');
        $email_msg->setReplyTo($this->user->mail);
        $email_msg->addToAddress($lead['email']);
        $email_msg->setTextBody($params['email_text']);
        $email_msg->setHTMLBody($params['email_text']);
        $email_msg->setSubject($params['subject']);
        $email_provider = \EmailProvider::getInstance();
        if($email_provider->sendEmail($email_msg) === false)
        {
            throw new Exception('Unable to send mail!', \Http\Rest\INTERNAL_ERROR);
        }
        return $response->withJson(array('email'=>true));
    }

    public function unlockEntry($request, $response, $args)
    {
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        if(!isset($args['name']))
        {
            return $response->withStatus(400);
        }
        $id = $args['name'];
        if(!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType))
        {
            throw new \Exception('Only admin users can unlock a registration');
        }
        $dataTable = $this->getDataTable();
        $filter = $this->getFilterForPrimaryKey($args['name']);
        $res = $dataTable->update($filter, array('final'=>false));
        return $response->withJson($res);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
