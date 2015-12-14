<?php
require_once("/var/www/secure_settings/class.FlipsideSettings.php");
class RegistrationDB
{
    private $client;
    private $manager;
    private $db;
    
    function __construct()
    {
        if(class_exists('MongoClient'))
        {
            $this->client = new MongoClient("mongodb://db.burningflipside.com/registrations", 
                                        array('username'=>FlipsideSettings::$mongo['registrations']['user'], 'password'=>FlipsideSettings::$mongo['registrations']['pwd']));
            $this->db     = $this->client->registrations;
            $this->manager = null;
        }
        else if(class_exists('\MongoDB\Driver\Manager'))
        {
            $this->client = null;
            $username = 'registration_rw';
            $password = '8Vcf6LNA';
            $this->manager = new \MongoDB\Driver\Manager("mongodb://$username:$password@db.burningflipside.com/registrations");
            $this->db = 'registrations';
        }
        else
        {
            throw new \Exception('No supported backends detected!');
        }
    }

    function getCurrentYear()
    {
        $cursor = false;
        if($this->manager !== null)
        {
            $query  = new \MongoDB\Driver\Query(array('name'=>'year'));
            $cursor = $this->manager->executeQuery($this->db.'.vars', $query);
        }
        else
        {
            $cursor = $this->db->vars->find(array('name'=>'year'));
            $cursor = $cursor->toArray();
        }
        var_dump($cursor); die();
        foreach($cursor as $doc)
        {
            return $doc['value'];
        }
        return false;
    }

    function getAllFromCollection($collection, $year = FALSE, $uid = false, $fields = false)
    {
        if($year === false)
        {
            $year = $this->getCurrentYear();
        }
        $col = $this->db->selectCollection($collection);
        $criteria = array();
        if($year !== '*')
        {
            $criteria['year'] = $year;
        }
        if($uid !== false)
        {
            $criteria['registrars'] = $uid;
        }
        $cursor = $col->find($criteria);
        if($fields !== false)
        {
            $cursor->fields($fields);
        }
        $ret    = array();
        foreach($cursor as $doc)
        {
            array_push($ret, $doc);
        }
        return $ret;
    }

    function searchFromCollection($collection, $criteria, $fields = false)
    {
        $col = $this->db->selectCollection($collection);
        foreach($criteria as $key=>$value)
        {
            if($value[0] === '/')
            {
                $criteria[$key] = array('$regex'=>new MongoRegex("$value"));
            }
        }
        $cursor = $col->find($criteria);
        if($fields !== false)
        {
            $cursor->fields($fields);
        }
        $ret    = array();
        foreach($cursor as $doc)
        {
            array_push($ret, $doc);
        }
        return $ret;
    }

    function getObjectFromCollectionByID($collection, $id)
    {
        $col = $this->db->selectCollection($collection);
        $cursor = $col->find(array('_id'=>new MongoId($id)));
        foreach($cursor as $doc)
        {
            return $doc;
        }
        return FALSE;
    }

    function addObjectToCollection($collection, $obj)
    {
         unset($obj['_id']);
         $col = $this->db->selectCollection($collection);
         $res = $col->insert($obj);
         if($res['ok'] == TRUE)
         {
             return $obj['_id'];
         }
         return FALSE;
    }

    function updateObjectInCollection($collection, $obj)
    {
        $id = new MongoId($obj['_id']);
        unset($obj['_id']);
        $col = $this->db->selectCollection($collection);
        $res = $col->update(array('_id' => $id), $obj);
        if($res['ok'] == TRUE)
        {
            return TRUE;
        }
        return FALSE;
    }

    function deleteObjectFromCollection($collection, $obj)
    {
        $id = new MongoId($obj['_id']);
        $col = $this->db->selectCollection($collection);
        $res = $col->remove(array('_id' => $id));
        if($res['ok'] == TRUE)
        {
            return TRUE;
        }
        return FALSE;
    }

    function getAllThemeCamps($year = FALSE)
    {
        return $this->getAllFromCollection('camps', $year);
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
        return $this->getAllFromCollection('camps', $year, $uid);
    }

    function getAllArtProjectsForUser($uid, $year = FALSE)
    {
        return $this->getAllFromCollection('art', $year, $uid);
    }

    function getAllArtCarsForUser($uid, $year = FALSE)
    {
        return $this->getAllFromCollection('dmv', $year, $uid);
    }

    function getAllEventsForUser($uid, $year = FALSE)
    {
        return $this->getAllFromCollection('event', $year, $uid);
    }

    function getThemeCampByID($id)
    {
        return $this->getObjectFromCollectionByID('camps', $id);
    }

    function getArtProjectByID($id)
    {
        return $this->getObjectFromCollectionByID('art', $id);
    }

    function getArtCarByID($id)
    {
        return $this->getObjectFromCollectionByID('dmv', $id);
    }

    function getEventByID($id)
    {
        return $this->getObjectFromCollectionByID('event', $id);
    }

    function deleteThemeCamp($camp)
    {
        return $this->db->camps->remove(array('_id'=>new MongoId($camp['_id'])));
    }

    function deleteArtProject($ap)
    {
        return $this->db->art->remove(array('_id'=>new MongoId($ap['_id'])));
    }

    function deleteArtCar($car)
    {
        return $this->db->dmv->remove(array('_id'=>new MongoId($car['_id'])));
    }

    function deleteEvent($event)
    {
        return $this->db->event->remove(array('_id'=>new MongoId($event['_id'])));
    }

    function addThemeCamp($camp)
    {
         unset($camp['_id']);
         $res = $this->db->camps->insert($camp);
         if($res['ok'] == TRUE)
         {
             return $camp['_id'];
         }
         return FALSE;
    }

    function addArtProject($ap)
    {
         unset($ap['_id']);
         $res = $this->db->art->insert($ap);
         if($res['ok'] == TRUE)
         {
             return $ap['_id'];
         }
         return FALSE;
    }

    function addArtCar($car)
    {
         unset($car['_id']);
         $res = $this->db->dmv->insert($car);
         if($res['ok'] == TRUE)
         {
             return $car['_id'];
         }
         return FALSE;
    }

    function addEvent($event)
    {
         unset($event['_id']);
         $res = $this->db->event->insert($event);
         if($res['ok'] == TRUE)
         {
             return $event['_id'];
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

    function updateArtCar($car)
    {
        $id = new MongoId($car['_id']);
        unset($car['_id']);
        $res = $this->db->dmv->update(array('_id' => $id), $car);
        if($res['ok'] == TRUE)
        {
            return TRUE;
        }
        return FALSE;
    }

    function updateEvent($event)
    {
        $id = new MongoId($event['_id']);
        unset($event['_id']);
        $res = $this->db->event->update(array('_id' => $id), $event);
        if($res['ok'] == TRUE)
        {
            return $event['_id'];
        }
        return FALSE;
    }
}
// vim: set tabstop=4 shiftwidth=4 expandtab:
?>
