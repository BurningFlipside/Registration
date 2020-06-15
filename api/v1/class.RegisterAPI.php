<?php
class RegisterAPI extends Flipside\Http\Rest\RestAPI
{
    public function setup($app)
    {
        $app->get('', array($this, 'getRoot'));
        $app->post('Actions/CompressImages', array($this, 'compressImages'));
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

    protected function optimizeImage($optimizer, $entry, $dataTable)
    {
        $logo = $entry['logo'];
        $base64size = strlen($logo);
        $data = explode( ',', $logo );
        $logo = base64_decode($data[1]);
        $oldBinSize = strlen($logo);
        $path = __DIR__.'/tmp/temp';
        $tmpFile = fopen($path, 'w');
        fwrite($tmpFile, $logo);
        fclose($tmpFile);
        $optimizer->optimize($path);
        $tmpFile = fopen($path, 'r');
        $stat = fstat($tmpFile);
        $newSize = $stat['size'];
        if($newSize < $oldBinSize)
        {
            $entry['logo'] = $data[0].','.base64_encode(fread($tmpFile, $stat['size']));
            $filter = new \Flipside\Data\Filter('_id eq '.$entries[$i]['_id']);
            $dataTable->update($filter, $entry);
        }
        unlink($path);
        return array('dbSize' => $base64size, 'oldSize' => $oldBinSize, 'newSize' => $newSize);
    }

    public function compressImages($request, $response, $args)
    {
        $factory = new \ImageOptimizer\OptimizerFactory();
        $optimizer = $factory->get();
        $dataTable = \Flipside\DataSetFactory::getDataTableByNames('registration', 'art');
        $entries = $dataTable->read();
        $count = count($entries);
        $ret = array();
        for($i = 0; $i < $count; $i++)
        {
            if(!isset($entries[$i]['logo']))
            {
                continue;
            }
            $tmp = $this->optimizeImage($optimizer, $entries[$i], $dataTable);
            array_push($ret, $tmp);
        }
        $dataSet = \Flipside\DataSetFactory::getDataSetByName('registration');
        $dataSet->runCommand(array('compact' => 'art'));
        $dataTable = \Flipside\DataSetFactory::getDataTableByNames('registration', 'camps');
        $entries = $dataTable->read();
        $count = count($entries);
        $ret = array();
        for($i = 0; $i < $count; $i++)
        {
            if(!isset($entries[$i]['logo']))
            {
                continue;
            }
            $tmp = $this->optimizeImage($optimizer, $entries[$i], $dataTable);
            array_push($ret, $tmp);
        }
        $dataSet = \Flipside\DataSetFactory::getDataSetByName('registration');
        $dataSet->runCommand(array('compact' => 'camps'));
        return $response->withJson($ret);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
