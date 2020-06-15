<?php
class TextAPI extends Flipside\Http\Rest\DataTableAPI
{
    public function __construct()
    {
        parent::__construct('registration', 'textStrings', 'name');
    }

    public function setup($app)
    {
        parent::setup($app);
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
        $tmp = array('text'=>$newObj);
        $newObj = array_merge($oldObj, $tmp);
        if(isset($newObj['_id']))
        {
            unset($newObj['_id']);
        }
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
        return $response->withJson($areas[0]['text']);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
