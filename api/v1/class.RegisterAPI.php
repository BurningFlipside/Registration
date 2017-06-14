<?php
class RegisterAPI extends Http\Rest\RestAPI
{
    public function setup($app)
    {
        $app->get('', array($this, 'getRoot'));
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
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
