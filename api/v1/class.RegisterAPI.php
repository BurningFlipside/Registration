<?php
class RegisterAPI extends Flipside\Http\Rest\RestAPI
{
    public function setup($app)
    {
        $app->get('', array($this, 'getRoot'));
        $app->post('Actions/RemoveOldImages', array($this, 'removeOldImages'));
    }

    public function getRoot($request, $response, $args)
    {
        $ret = array();
        $root = $request->getUri()->getBasePath();
        $ret['art'] = $root.'/art';
        $ret['camps'] = $root.'/camps';
        $ret['dmv'] = $root.'/dmv';
        $ret['event'] = $root.'/event';
        $ret['vars'] = $root.'/vars';
        return $response->withJson($ret);
    }

    public function removeOldImages($request, $response, $args)
    {
        $this->validateLoggedIn($request);
        if(!$this->user->isInGroupNamed('RegistrationAdmins'))
        {
            return $response->withStatus(403);
        }
        $dataTable = \Flipside\DataSetFactory::getDataTableByNames('registration', 'art');
        $res = $dataTable->update(new \Flipside\Data\Filter('year eq 2015'), array('logo'=>''));
        return $response->withStatus(200);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
