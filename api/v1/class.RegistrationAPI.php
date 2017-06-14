<?php
class RegistrationAPI extends Http\Rest\DataTableAPI
{
    protected $adminType;

    public function __construct($dataTable, $adminType)
    {
        parent::__construct('registration', $dataTable, '_id');
        $this->adminType = $adminType;
    }

    public function set($app)
    {
        parent::setup($app);
        $app->get('/{name}/{field}[/]', array($this, 'readEntryField'));
        $app->post('/{name}/contact[/{lead}]', 'contactLead');
        $app->post('/{name}/Actions/Unlock', 'unlockEntry');
    }

    protected function processEntry($obj, $request)
    {
        foreach($obj as $key=>$value)
        {
            if($key == '_id')
            {
                $obj['_id'] = (string)$obj['_id'];
            }
            else if(is_object($value) || is_array($value))
            {
                if($key === 'value') continue;
                if(!$this->user->isInGroupNamed('RegistrationAdmins') && !$this->user->isInGroupNamed($this->adminType))
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
        return new \Data\Filter($this->primaryKeyName.' eq '.new MongoId($value));
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
        if(isset($queryParams['no_logo']))
        {
            return array('fields'=>array('logo' => false, 'image' => false));
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
        if(!in_array($app->user->uid, $obj['registrars']))
        {
            array_push($obj['registrars'], $app->user->uid);
        }
        return true;
    }

    protected function validateUpdate(&$newObj, $request, $oldObj)
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
        $obj['registrars'] = array_merge($obj['registrars'], $oldObj['registrars']);
        if($this->user->isInGroupNamed('RegistrationAdmins') || $this->user->isInGroupNamed($this->adminType))
        {
            array_push($obj['registrars'], $this->user->uid);
        }
        $obj['registrars'] = array_unique($obj['registrars']);
        if(!isset($obj['_id']))
        {
             $obj['_id'] = (string)$oldObj['_id'];
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

    public function readEntryField($request, $response, $args)
    {
        if($args['name'] !== '*')
        {
            $odata = $request->getAttribute('odata', new \ODataParams(array()));
            $odata->select = array($args['field']);
            $request = $request->withAttribute('odata', $odata);
            return parent::readEntry($request, $response, $args);
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
            if(isset($objs[$i][$field]))
            {
                array_push($res, $objs[$i][$field]);
            }
        }
        return $response->withJson($res);
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
        $res = $dataTable->update($filter, array('$unset'=>array('final'=>true)));
        return $response->withJson($res);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
