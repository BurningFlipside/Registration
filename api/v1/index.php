<?php
require_once('Autoload.php');
require_once('class.FlipREST.php');

if($_SERVER['REQUEST_URI'][0] == '/' && $_SERVER['REQUEST_URI'][1] == '/')
{
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 1);
}

$app = new FlipREST();
$app->group('/art', 'art');
$app->group('/camps', 'camps');
$app->group('/dmv', 'dmv');
$app->group('/event', 'event');

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
   else if(in_array($user->uid[0], $obj['registrars']))
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
    if(validate_user_is_admin($app->user, $collection) && isset($params['filter']))
    {
        $filter = new \Data\Filter($params['filter']);
    }
    $register_data_set = DataSetFactory::get_data_set('registration');
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
    $register_data_set = DataSetFactory::get_data_set('registration');
    $data_table = $register_data_set[$collection];
    $objs = $data_table->read(false);
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
    $register_data_set = DataSetFactory::get_data_set('registration');
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
                    $str = substr($ap[$field], 5);
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
    $db = new RegistrationDB();
    $obj = $app->request->params();
    if($obj === null || count($obj) === 0)
    {
        $body = $app->request->getBody();
        $obj  = json_decode($body);
        $obj  = get_object_vars($obj);
    }
    //Ensure minimum fields are set...
    if(!isset($obj['name']) || !isset($obj['teaser']) || !isset($obj['description']))
    {
        throw new Exception('Missing one or more required parameters!', INTERNAL_ERROR);
    }
    $obj['year'] = $db->getCurrentYear();
    if(!isset($obj['registrars']))
    {
        $obj['registrars'] = array();
    }
    array_push($obj['registrars'], $app->user->uid[0]);
    if(isset($obj['_id']) && strlen($obj['_id']) > 0)
    {
        $app->redirect($collection.'/'.$obj['_id'], 307);
        return;
    }
    else
    {
        $res = $db->addObjectToCollection($collection, $obj);
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
    $db = new RegistrationDB();
    $old_obj = $db->getObjectFromCollectionByID($collection, $id);
    if(validate_user_has_access($app->user, $old_obj, $collection) === FALSE)
    {
        throw new Exception('Cannot edit object that is not yours', ACCESS_DENIED);
    }
    $obj = $app->request->params();
    if($obj === null || count($obj) === 0)
    {
        $body = $app->request->getBody();
        $obj  = json_decode($body);
        $obj  = get_object_vars($obj);
    }
    //Ensure minimum fields are set...
    if(!isset($obj['name']) || !isset($obj['teaser']) || !isset($obj['description']))
    {
        throw new Exception('Missing one or more required parameters!', INTERNAL_ERROR);
    }
    $obj['year'] = $db->getCurrentYear();
    if(!isset($obj['registrars']))
    {
        $obj['registrars'] = array();
    }
    $obj['registrars'] = array_merge($obj['registrars'], $old_obj['registrars']);
    if(validate_user_is_admin($app->user, $collection) === FALSE)
    {
        array_push($obj['registrars'], $app->user->uid[0]);
    }
    if(!isset($obj['_id']))
    {
        $obj['_id'] = $id;
    }
    $res = $db->updateObjectInCollection($collection, $obj);
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
    $db = new RegistrationDB();
    $old_obj = $db->getObjectFromCollectionByID($collection, $id);
    if(validate_user_has_access($app->user, $old_obj, $collection) === FALSE)
    {
        throw new Exception('Cannot delete object that is not yours', ACCESS_DENIED);
    }
    $res = $db->deleteObjectFromCollection($collection, $old_obj);
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

function art()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
    $app->post('/:id/contact(/:lead)', 'obj_contact');
}

function camps()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
    $app->post('/:id/contact(/:lead)', 'obj_contact');
}

function dmv()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
}

function event()
{
    global $app;
    $app->get('', 'list_obj');
    $app->get('/:id(/:field)', 'obj_view');
    $app->post('', 'obj_add');
    $app->post('/:id', 'obj_edit');
    $app->put('/:id', 'obj_edit');
    $app->delete('/:id', 'obj_delete');
}

$app->run();
?>
