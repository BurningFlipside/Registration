<?php
require_once("/var/www/secure_settings/class.FlipsideSettings.php");
require_once('class.ThemeCamp.php');
class RegistrationDB
{
    private $client;
    private $db;
    
    function __construct()
    {
        $this->client = new MongoClient("mongodb://db.burningflipside.com/registrations", 
                                        array('username'=>FlipsideSettings::$mongo['registrations']['user'], 'password'=>FlipsideSettings::$mongo['registrations']['pwd']));
        $this->db     = $this->client->registrations;
    }

    function getCurrentYear()
    {
        $cursor = $this->db->vars->find(array('name'=>'year'));
        foreach($cursor as $doc)
        {
            return $doc['value'];
        }
        return FALSE;
    }

    function getAllFromCollection($collection, $year = FALSE)
    {
        if($year == FALSE)
        {
            $year = $this->getCurrentYear();
        }
        $col = $this->db->selectCollection($collection);
        $cursor = $col->find(array('year'=>$year));
        $ret    = array();
        foreach($cursor as $doc)
        {
            array_push($ret, $doc);
        }
        return $ret;
    }

    function getAllThemeCamps($year = FALSE)
    {
        return $this->getAllFromCollection('tc', $year);
    }

    function getAllArtProjects($year = FALSE)
    {
        return $this->getAllFromCollection('art', $year);
    }

    function getAllArtCars($year = FALSE)
    {
        return $this->getAllFromCollection('dmv', $year);
    }

    function getAllEvents($year = FALSE)
    {
        return $this->getAllFromCollection('event', $year);
    }

    function getAllThemeCampsForUser($uid, $year = FALSE)
    {
        if($year == FALSE)
        {
            $year = $this->getCurrentYear();
        }
        if($year == '*')
        {
            $cursor = $this->db->camps->find(array('registrars'=>$uid));
        }
        else
        {
            $cursor = $this->db->camps->find(array('registrars'=>$uid, 'year'=>$year));
        }
        $ret    = array();
        foreach($cursor as $doc)
        {
            array_push($ret, $doc);
        }
        return $ret;
    }

    function getThemeCampByID($id)
    {
        $cursor = $this->db->camps->find(array('_id'=>new MongoId($id)));
        foreach($cursor as $doc)
        {
            return $doc;
        }
        return FALSE;
    }

    function deleteThemeCamp($camp)
    {
        return $this->db->camps->remove(array('_id'=>new MongoId($camp['_id'])));
    }

    function addThemeCamp($camp)
    {
         $array = $camp;
         unset($array['_id']);
         $res = $this->db->camps->insert($array);
         if($res['ok'] == TRUE)
         {
             return $array['_id'];
         }
         return FALSE;
    }

    function updateThemeCamp($camp)
    {
        $id = new MongoId($camp['_id']);
        unset($camp['_id']);
        $res = $this->db->camps->update(array('_id' => $id), $camp);
        if($res['ok'] == TRUE)
        {
            return TRUE;
        }
        return FALSE;
    } 

    function getAllArtProjectsForUser($uid, $year = FALSE)
    {
        if($year == FALSE)
        {
            $year = $this->getCurrentYear();
        }
        if($year == '*')
        {
            $cursor = $this->db->art->find(array('registrars'=>$uid));
        }
        else
        {
            $cursor = $this->db->art->find(array('registrars'=>$uid, 'year'=>$year));
        }
        $ret    = array();
        foreach($cursor as $doc)
        {
            array_push($ret, $doc);
        }
        return $ret;
    }

    function getArtProjectByID($id)
    {
        $cursor = $this->db->art->find(array('_id'=>new MongoId($id)));
        foreach($cursor as $doc)
        {
            return $doc;
        }
        return FALSE;
    }

    function deleteArtProject($ap)
    {
        return $this->db->art->remove(array('_id'=>new MongoId($ap['_id'])));
    }

    function addArtProject($ap)
    {
         $array = $ap;
         unset($array['_id']);
         $res = $this->db->art->insert($array);
         if($res['ok'] == TRUE)
         {
             return $array['_id'];
         }
         return FALSE;
    }

    function updateArtProject($ap)
    {
        $id = new MongoId($ap['_id']);
        unset($ap['_id']);
        $res = $this->db->art->update(array('_id' => $id), $ap);
        if($res['ok'] == TRUE)
        {
            return TRUE;
        }
        return FALSE;
    }

    
}
// vim: set tabstop=4 shiftwidth=4 expandtab:
?>
