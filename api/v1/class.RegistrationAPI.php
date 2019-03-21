<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class RegistrationAPI extends Http\Rest\DataTableAPI
{
    protected $adminType;

    public function __construct($dataTable, $adminType, $htmlRenderer=false, $email=false)
    {
        parent::__construct('registration', $dataTable, '_id');
        $this->adminType = $adminType;
        $this->htmlRender = $htmlRenderer;
        $this->email = $email;
    }

    public function setup($app)
    {
        $app->get('/xlsx', array($this, 'getSpreadSheet'));
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
        if(isset($obj['final']) && $obj['final'] === true)
        {
            if(isset($this->email) && $this->email !== false)
            {
                $email = new $this->email($obj);
                $email_provider = EmailProvider::getInstance();
                if($email_provider->sendEmail($email) === false)
                {
                    throw new \Exception('Unable to send ticket email!');
                }
            }
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
        if(isset($newObj['final']) && $newObj['final'] === true)
        {
            if(isset($this->email) && $this->email !== false)
            {
                $email = new $this->email(array_merge($oldObj, $newObj));
                $email_provider = EmailProvider::getInstance();
                if($email_provider->sendEmail($email) === false)
                {
                    throw new \Exception('Unable to send ticket email!');
                }
            }
        }
    }

    public function createEntry($request, $response, $args)
    {
        try
        {
            return parent::createEntry($request, $response, $args);
        }
        catch(Exception $e)
        {
            if($e->getCode() === 11000)
            {
                return $this->updateEntry($request, $response, $args);
            }
            else
            {
                return $response->withJson($e, 500);
            }
        }
    }

    public function readEntries($request, $response, $args)
    {
        $overrides = $request->getAttribute('serializeOverrides');
        if($overrides !== null)
        {
            $overrides['text/html'] = $this->htmlRender;
        }
        return parent::readEntries($request, $response, $args);
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
                if(!isset($obj[$field]))
                {
                    return $response->withJson(null);
                }
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
        $overrides = $request->getAttribute('serializeOverrides');
        if($overrides !== null)
        {
            $overrides['text/html'] = $this->htmlRender;
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

    protected function getArtSpreadSheet($objs)
    {
        $ssheat = new Spreadsheet();
        $contacts = $ssheat->getActiveSheet();
        $contacts->setTitle('Contacts');
        $contacts->getCellByColumnAndRow(1, 1)->setValue('Project Name')->getStyle()->getFont()->setBold(true);
        $contacts->getCellByColumnAndRow(2, 1)->setValue('Project Lead')->getStyle()->getFont()->setBold(true);
        $contacts->getCellByColumnAndRow(3, 1)->setValue('Burner Name')->getStyle()->getFont()->setBold(true);
        $contacts->getCellByColumnAndRow(4, 1)->setValue('Email')->getStyle()->getFont()->setBold(true);
        $contacts->getCellByColumnAndRow(5, 1)->setValue('Phone')->getStyle()->getFont()->setBold(true);
        $contacts->getCellByColumnAndRow(6, 1)->setValue('Can Text')->getStyle()->getFont()->setBold(true);
        $fire = $ssheat->createSheet();
        $fire->setTitle('Fire!');
        $fire->getCellByColumnAndRow(1, 1)->setValue('Project Name')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(2, 1)->setValue('Teaser')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(3, 1)->setValue('Description')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(4, 1)->setValue('Has Flame Effects')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(5, 1)->setValue('Flame Effects')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(6, 1)->setValue('Burn Day')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(7, 1)->setValue('Burn Plan')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(8, 1)->setValue('Cleanup Plan')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(9, 1)->setValue('Fire Lead')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(10, 1)->setValue('Burner Name')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(11, 1)->setValue('Email')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(12, 1)->setValue('Phone')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(13, 1)->setValue('Can Text')->getStyle()->getFont()->setBold(true);
        $fire->getCellByColumnAndRow(14, 1)->setValue('Camp')->getStyle()->getFont()->setBold(true);
        $count = count($objs);
        $fireIdx = 0;
        for($i = 0; $i < $count; $i++)
        {
            if(is_object($objs[$i]))
            {
                $objs[$i] = (array)$objs[$i];
            }
            $contacts->setCellValueByColumnAndRow(1, $i+2, $objs[$i]['name']);
            $contacts->setCellValueByColumnAndRow(2, $i+2, $objs[$i]['artLead']['name']);
            $contacts->setCellValueByColumnAndRow(3, $i+2, $objs[$i]['artLead']['burnerName']);
            $contacts->setCellValueByColumnAndRow(4, $i+2, $objs[$i]['artLead']['email']);
            if($objs[$i]['artLead']['phone'])
            {
                $contacts->setCellValueByColumnAndRow(5, $i+2, $objs[$i]['artLead']['phone']);
            }
            if($objs[$i]['artLead']['sms'])
            {
                $contacts->setCellValueByColumnAndRow(6, $i+2, 1);
            }
            if(isset($objs[$i]['fire']))
            {
                $fire->setCellValueByColumnAndRow(1, $fireIdx+2, $objs[$i]['name']);
                $fire->getStyleByColumnAndRow(2, $fireIdx+2)->getAlignment()->setWrapText(true);
                $fire->setCellValueByColumnAndRow(2, $fireIdx+2, $objs[$i]['teaser']);
                $fire->getStyleByColumnAndRow(3, $fireIdx+2)->getAlignment()->setWrapText(true);
                $fire->setCellValueByColumnAndRow(3, $fireIdx+2, $objs[$i]['description']);
                if(isset($objs[$i]['fire']['hasFlameEffects']) && $objs[$i]['fire']['hasFlameEffects'])
                {
                    $fire->setCellValueByColumnAndRow(4, $fireIdx+2, 1);
                }
                $fire->getStyleByColumnAndRow(5, $fireIdx+2)->getAlignment()->setWrapText(true);
                $fire->setCellValueByColumnAndRow(5, $fireIdx+2, $objs[$i]['fire']['flameEffects']);
                if(isset($objs[$i]['fire']['burnDay']))
                {
                    $fire->setCellValueByColumnAndRow(6, $fireIdx+2, $objs[$i]['fire']['burnDay']);
                }
                $fire->getStyleByColumnAndRow(7, $fireIdx+2)->getAlignment()->setWrapText(true);
                $fire->setCellValueByColumnAndRow(7, $fireIdx+2, $objs[$i]['fire']['burnPlan']);
                if(isset($objs[$i]['fire']['cleanupPlan']))
                {
                    $fire->getStyleByColumnAndRow(8, $fireIdx+2)->getAlignment()->setWrapText(true);
                    $fire->setCellValueByColumnAndRow(8, $fireIdx+2, $objs[$i]['fire']['cleanupPlan']);
                }
                $fireIdx++;
            }
        }
        $contacts->getColumnDimensionByColumn(1)->setAutoSize(true);
        $contacts->getColumnDimensionByColumn(2)->setAutoSize(true);
        $contacts->getColumnDimensionByColumn(3)->setAutoSize(true);
        $contacts->getColumnDimensionByColumn(4)->setAutoSize(true);
        $contacts->getColumnDimensionByColumn(5)->setAutoSize(true);
        $contacts->getColumnDimensionByColumn(6)->setAutoSize(true);
        $contacts->setAutoFilter('A1:F'.($count+1));
        $fire->getColumnDimensionByColumn(1)->setWidth(59.0);
        $fire->getColumnDimensionByColumn(2)->setWidth(70.0);
        $fire->getColumnDimensionByColumn(3)->setWidth(100.0);
        $fire->getColumnDimensionByColumn(5)->setWidth(100.0);
        $fire->getColumnDimensionByColumn(7)->setWidth(100.0);
        $ssheat->setActiveSheetIndex(0);
        return $ssheat;
    }

    public function getSpreadSheet($request, $response, $args)
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
        $objs = $dataTable->read($odata->filter, $odata->select, $odata->top,
                                 $odata->skip, $odata->orderby);
        switch($this->dataTableName)
        {
            case 'art':
                $ssheet = $this->getArtSpreadSheet($objs);
        }
        $writer = new Xlsx($ssheet);
        $response = $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        ob_start();
        $writer->save('php://output');
        $string = ob_get_clean();
        $body = $response->getBody();
        $body->write($string);
        return $response;
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
