<?php
class VariablesAPI extends Flipside\Http\Rest\DataTableAPI
{
    public function __construct()
    {
        parent::__construct('registration', 'vars', 'name');
    }

    public function setup($app)
    {
        parent::setup($app);
        $app->patch('/{name}/{subname}', array($this, 'updateSubEntry'));
    }

    protected function processEntry($obj)
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
                unset($obj[$key]);
            }
        }
        return $obj;
    }

    protected function canCreate($request)
    {
        $this->validateLoggedIn($request);
        return $this->user->isInGroupNamed('RegistrationAdmins');
    }

    protected function canUpdate($request, $entity)
    {
        $this->validateLoggedIn($request);
        return $this->user->isInGroupNamed('RegistrationAdmins');
    }

    protected function validateUpdate(&$newObj, $request, $oldObj)
    {
        $tmp = array('value'=>$newObj);
        $newObj = array_merge($oldObj, $tmp);
        return true;
    }

    public function readEntry($request, $response, $args)
    {
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        $dataTable = $this->getDataTable();
        $odata = $request->getAttribute('odata', new \Flipside\ODataParams(array()));
        $filter = $this->getFilterForPrimaryKey($args['name']);
        $areas = $dataTable->read($filter, $odata->select, $odata->top,
                                  $odata->skip, $odata->orderby);
        if(empty($areas))
        {
            return $response->withStatus(404);
        }
        if(method_exists($this, 'processEntry'))
        {
            $areas[0] = $this->processEntry($areas[0]);
        }
        return $response->withJson($areas[0]['value']);
    }

    public function updateSubEntry($request, $response, $args)
    {
        if($this->canRead($request) === false)
        {
            return $response->withStatus(401);
        }
        $filter = $this->getFilterForPrimaryKey($args['name']);
        $dataTable = $this->getDataTable();
        $entry = $dataTable->read($filter);
        if(empty($entry))
        {
            return $response->withStatus(404);
        }
        if(count($entry) === 1 && isset($entry[0]))
        {
            $entry = $entry[0];
        }
        if($this->canUpdate($request, $entry) === false)
        {
            return $response->withStatus(401);
        }
        $value = $entry['value'];
        if(!isset($value[$args['subname']]))
        {
            return $response->withStatus(404);
        }
        $obj = $request->getParsedBody();
        if($obj === null)
        {
            $request->getBody()->rewind();
            $obj = $request->getBody()->getContents();
            $tmp = json_decode($obj, true);
            if($tmp !== null)
            {
                $obj = $tmp;
            }
        }
        $entry['value'][$args['subname']] = $obj;
        $ret = $dataTable->update($filter, $entry);
        return $response->withJson($ret);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
