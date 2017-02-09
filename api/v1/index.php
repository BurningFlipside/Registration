<?php
require_once('Autoload.php');
require_once('class.FlipREST.php');

if($_SERVER['REQUEST_URI'][0] == '/' && $_SERVER['REQUEST_URI'][1] == '/')
{
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 1);
}

$app = new FlipREST();
$app->get('(/)', 'getRoot');
$app->get('/\$all', 'getAll');
$app->group('/art', 'art');
$app->group('/camps', 'camps');
$app->group('/dmv', 'dmv');
$app->group('/event', 'event');
$app->group('/vars', 'vars');

function getRoot()
{
    global $app;
    $ret = array();
    $root = $app->request->getRootUri();
    $ret['art'] = $root.'/art';
    $ret['camps'] = $root.'/camps';
    $ret['dmv'] = $root.'/dmv';
    $ret['event'] = $root.'/event';
    $ret['vars'] = $root.'/vars';
    echo json_encode($ret);
}

function get_collection_name()
{
    global $app;
    $collection = substr($app->request->getPathInfo(), 1);
    return strtok($collection, '/');
}

function trim_obj(&$obj)
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
}

function validate_user_is_admin($user, $collection)
{
   if($user->isInGroupNamed('RegistrationAdmins'))
   {
       return true;
   }
   $admins = array(
       'art'   => 'ArtAdmins',
       'camps' => 'CampAdmins',
       'dmv'   => 'DMVAdmins',
       'event' => 'EventAdmins'
   );
   if(isset($admins[$collection]) && $user->isInGroupNamed($admins[$collection]))
   {
       return true;
   }
   return false;
}

function validate_user_has_access($user, $obj, $collection)
{
   if($user->isInGroupNamed('RegistrationAdmins'))
   {
       return true;
   }
   else if(in_array($user->uid, $obj['registrars']))
   {
       return true;
   }
   $admins = array(
       'art'   => 'ArtAdmins',
       'camps' => 'CampAdmins',
       'dmv'   => 'DMVAdmins',
       'event' => 'EventAdmins'
   );
   if(isset($admins[$collection]) && $user->isInGroupNamed($admins[$collection]))
   {
       return true;
   }
   return false;
}

function getAll()
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collections = array();
    if(validate_user_is_admin($app->user, 'art'))
    {
        $collections[] = 'art';
    }
    if(validate_user_is_admin($app->user, 'camps'))
    {
        $collections[] = 'camps';
    }
    if(validate_user_is_admin($app->user, 'dmv'))
    {
        $collections[] = 'dmv';
    }
    if(validate_user_is_admin($app->user, 'event'))
    {
        $collections[] = 'event';
    }
    $count = count($collections);
    if($count === 0)
    {
        echo 'false';
    }
    $res = array();
    $register_data_set = DataSetFactory::get_data_set('registration');
    for($i = 0; $i < $count; $i++)
    {
        $data_table = $register_data_set[$collections[$i]];
        $data = $data_table->read($app->odata->filter, $app->odata->select, $app->odata->top, $app->odata->skip, $app->odata->orderby);
        $res = array_merge($res, $data);
    }
    echo json_encode($res);
}

function list_obj()
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $params = $app->request->params();
    $filter = false;
    $collection = get_collection_name();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    if(validate_user_is_admin($app->user, $collection) && $app->odata->filter !== false)
    {
        $filter = $app->odata->filter;
    }
    else
    {
        $arr = $register_data_set['vars']->read(new \Data\Filter("name eq 'year'"));
        $filter = new \Data\Filter('year eq '.$arr[0]['value']);
    }
    $data_table = $register_data_set[$collection];
    $ret = array();
    if($app->odata->count)
    {
        $ret['@odata.count'] = $data_table->count($filter);
    }
    $mongo_params = array();
    if(isset($params['no_logo']))
    {
        $mongo_params['fields'] = array('logo' => false);
    }
    $data = $data_table->read($filter, $app->odata->select, $app->odata->top, $app->odata->skip, $app->odata->orderby, $mongo_params);
    if(!validate_user_is_admin($app->user, $collection))
    {
        $count = count($data);
        for($i = 0; $i < $count; $i++)
        {
            trim_obj($data[$i]);
        }
    }
    if(isset($ret['@odata.count']))
    {
        $ret['value'] = $data;
        echo json_encode($ret);
    }
    else
    {
        echo json_encode($data);
    }
}

function obj_list_with_filter($field)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collection = get_collection_name();
    if(!validate_user_is_admin($app->user, $collection))
    {
        throw new Exception('User not admin', ACCESS_DENIED);
    }
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $data_table = $register_data_set[$collection];
    $objs = $data_table->read($app->odata->filter);
    $res = array();
    $count = count($objs);
    for($i = 0; $i < $count; $i++)
    {
        if(isset($objs[$i][$field]))
        {
            array_push($res, $objs[$i][$field]);
        }
    }
    echo json_encode($res);
}

function obj_search()
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collection = get_collection_name();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $params = $app->request->params();
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
        $arr = $register_data_set['vars']->read(new \Data\Filter("name eq 'year'"));
        $params['year'] = $arr[0]['value'];
    }
    else if($params['year'] === '*')
    {
        unset($params['year']);
    }
    $data_table = $register_data_set[$collection];
    $objs = $data_table->read($params);
    echo json_encode($objs);
}

function obj_view($id, $field = FALSE)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    if($id === '*')
    {
        obj_list_with_filter($field);
        return;
    }
    $collection = get_collection_name();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $data_table = $register_data_set[$collection];
    $mongo_params = array();
    if(isset($params['no_logo']))
    {
        $mongo_params['fields'] = array('logo' => false);
    }
    $filter = new \Data\Filter('_id eq '.new MongoId($id));
    $data = $data_table->read($filter, $app->odata->select, false, false, false, $mongo_params);
    if($data === false || !isset($data[0]))
    {
        throw new Exception('Unable to obtain object!', INTERNAL_ERROR);
    }
    else
    {
        $obj = $data[0];
        if($app->request->params('full') === null)
        {
            if(!validate_user_is_admin($app->user, $collection))
            {
               trim_obj($obj);
            }
            else if($field === false)
            {
                trim_obj($obj);
            }
            if($field !== FALSE)
            {
                if(!is_array($obj[$field]) && strncmp($obj[$field], 'data:', 5) === 0)
                {
                    $app->fmt = 'passthru';
                    $str = substr($obj[$field], 5);
                    $type = strtok($str, ';');
                    strtok(',');
                    $str = strtok("\0");
                    print(base64_decode($str));
                    $app->response->headers->set('Content-Type', $type);
                }
                else
                {
                    echo json_encode($obj[$field]);
                }
                return;
            }
        }
        else
        {
            if(validate_user_has_access($app->user, $obj, $collection) === FALSE)
            {
                throw new Exception('Cannot edit object that is not yours', ACCESS_DENIED);
            }
        }
        echo json_encode($obj);
    }
}

function obj_add()
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collection = get_collection_name();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $data_table = $register_data_set[$collection];
    $obj = $app->getJsonBody(true);
    //Ensure minimum fields are set...
    if(!isset($obj['name']) || !isset($obj['teaser']) || !isset($obj['description']))
    {
        throw new Exception('Missing one or more required parameters!', INTERNAL_ERROR);
    }
    $arr = $register_data_set['vars']->read(new \Data\Filter("name eq 'year'"));
    $obj['year'] = $arr[0]['value'];
    if(!isset($obj['registrars']))
    {
        $obj['registrars'] = array();
    }
    if(!in_array($app->user->uid, $obj['registrars']))
    {
        array_push($obj['registrars'], $app->user->uid);
    }
    if(isset($obj['_id']) && strlen($obj['_id']) > 0)
    {
        $app->redirect($collection.'/'.$obj['_id'], 307);
        return;
    }
    else
    {
        $res = $data_table->create($obj);
    }
    if($res === FALSE)
    {
        throw new Exception('Unable to add art project!', INTERNAL_ERROR);
    }
    else
    {
        echo json_encode(array('_id'=>(string)$res, 'url'=>$app->request->getUrl().$app->request->getPath().'/'.(string)$res));
    }
}

function obj_edit($id)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collection = get_collection_name();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $data_table = $register_data_set[$collection];
    $filter  = new \Data\Filter("_id eq $id");
    $old_obj = $data_table->read($filter);
    $old_obj = $old_obj[0];
    if(validate_user_has_access($app->user, $old_obj, $collection) === FALSE)
    {
        throw new Exception('Cannot edit object that is not yours', ACCESS_DENIED);
    }
    $obj = $app->getJsonBody(true);
    //Ensure minimum fields are set...
    if(!isset($obj['name']) || !isset($obj['teaser']) || !isset($obj['description']))
    {
        throw new Exception('Missing one or more required parameters!', INTERNAL_ERROR);
    }
    $arr = $register_data_set['vars']->read(new \Data\Filter("name eq 'year'"));
    $obj['year'] = $arr[0]['value'];
    if(!isset($obj['registrars']))
    {
        $obj['registrars'] = array();
    }
    $obj['registrars'] = array_merge($obj['registrars'], $old_obj['registrars']);
    if(validate_user_is_admin($app->user, $collection) === FALSE)
    {
        array_push($obj['registrars'], $app->user->uid);
    }
    if(!isset($obj['_id']))
    {
        $obj['_id'] = $id;
    }
    $res = $data_table->update($filter, $obj);
    if($res === FALSE)
    {
        throw new Exception('Unable to update object!', INTERNAL_ERROR);
    }
    else
    {
        echo json_encode(array('update'=>TRUE));
    }
}

function obj_delete($id)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collection = get_collection_name();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $data_table = $register_data_set[$collection];
    $filter  = new \Data\Filter("_id eq $id");
    $old_obj = $data_table->read($filter);
    $old_obj = $old_obj[0];
    if(validate_user_has_access($app->user, $old_obj, $collection) === FALSE)
    {
        throw new Exception('Cannot delete object that is not yours', ACCESS_DENIED);
    }
    $res = $data_table->delete($filter);
    if($res === FALSE)
    {
        throw new Exception('Unable to delete object!', INTERNAL_ERROR);
    }
    else
    {
        echo json_encode(array('delete'=>TRUE));
    }
}

function obj_contact($id, $lead = FALSE)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    require_once('class.FlipsideMail.php');
    $collection = get_collection_name();
    $db = new RegistrationDB();
    $obj = $db->getObjectFromCollectionByID($collection, $id);
    if($lead === FALSE)
    {
        $defaults = array(
            'art'   => 'artLead',
            'camps' => 'campLead'
        );
        if(isset($defaults[$collection]))
        {
            $lead = $defaults[$collection];
        }
        if($lead === FALSE)
        {
            throw new Exception('No default lead for '.$collection.'!', INTERNAL_ERROR);
        }
    }
    $params = $app->request->params();
    if($params === null || (!isset($params['subject']) || !isset($params['email_text'])))
    {
        $body   = $app->request->getBody();
        $params = json_decode($body);
        $params = get_object_vars($params);
    }
    $lead = $obj[$lead];
    $mail = new FlipsideMail();
    $email = array(
        'reply_to'  => $app->user->mail[0],
        'from_name' => 'Burning Flipside Contact Form',
        'to'        => $lead['email'],
        'subject'   => $params['subject'],
        'body'      => $params['email_text'],
        'alt_body'  => $params['email_text']
    );
    $ret = $mail->send_HTML($email);
    if($ret === FALSE)
    {
         throw new Exception('Unable to send mail! '.$mail->ErrorInfo, INTERNAL_ERROR);
    }
    else
    {
        echo json_encode(array('email'=>TRUE));
    }
}

function objUnlock($id)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $collection = get_collection_name();
    if(validate_user_is_admin($app->user, $collection) === false)
    {
        throw new \Exception('Only admin users can unlock a registration');
    }
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $data_table = $register_data_set[$collection];
    $filter  = new \Data\Filter("_id eq $id");
    $res = $data_table->update($filter, array('$unset'=>array('final'=>true)));
    echo json_encode($res);
}

function list_vars()
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    else if(!$app->user->isInGroupNamed('RegistrationAdmins'))
    {
        throw new Exception('Must be RegistrationAdmins', ACCESS_DENIED);
    }
    $params = $app->request->params();
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $dataTable = $register_data_set['vars'];
    $data = $dataTable->read();
    $count = count($data);
    for($i = 0; $i < $count; $i++)
    {
        trim_obj($data[$i]);
    }
    echo json_encode($data);
}

function create_var()
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    else if(!$app->user->isInGroupNamed('RegistrationAdmins'))
    {
        throw new Exception('Must be RegistrationAdmins', ACCESS_DENIED);
    }
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $dataTable = $register_data_set['vars'];
    $obj = $app->getJsonBody();
    $dataTable->create($obj);
}

function get_var($name)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $dataTable = $register_data_set['vars'];
    $data = $dataTable->read(new \Data\Filter("name eq '$name'"));
    echo json_encode($data[0]['value']);
}

function updateVar($name)
{
    global $app;
    if(!$app->user)
    {
        throw new Exception('Must be logged in', ACCESS_DENIED);
    }
    else if(!$app->user->isInGroupNamed('RegistrationAdmins'))
    {
        throw new Exception('Must be RegistrationAdmins', ACCESS_DENIED);
    }
    $register_data_set = DataSetFactory::getDataSetByName('registration');
    $dataTable = $register_data_set['vars'];
    $filter = new \Data\Filter("name eq '$name'");
    $obj = $app->getJsonBody(true);
    $res = $dataTable->update($filter, array('value'=>$obj));
    echo json_encode($res);
}

function art()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/Actions/Search', 'obj_search');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
    $app->post('/:id/contact(/:lead)', 'obj_contact');
    $app->post('/:id/Actions/Unlock', 'objUnlock');
}

function camps()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/Actions/Search', 'obj_search');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
    $app->post('/:id/contact(/:lead)', 'obj_contact');
    $app->post('/:id/Actions/Unlock', 'objUnlock');
}

function dmv()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/Actions/Search', 'obj_search');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
    $app->post('/:id/Actions/Unlock', 'objUnlock');
}

function event()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/Actions/Search', 'obj_search');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
    $app->post('/:id/Actions/Unlock', 'objUnlock');
}

function vars()
{
    global $app;
    $app->get('', 'list_vars');
    $app->post('', 'create_var');
    $app->get('/:name', 'get_var');
    $app->patch('/:name', 'updateVar');
}

$app->run();
?>
