<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new SecurePage('Burning Flipside - Registration');

$camps    = array();
$arts     = array();
$cars     = array();
$events   = array();
$tcDates  = array();
$artDates = array();
$dmvDates = array();
$evtDates = array();

$now = time();
$secsInDay = 86399;

$data_set = DataSetFactory::getDataSetByName('registration');
$vars_data_table = $data_set['vars'];

$vars = $vars_data_table->read(new \Data\Filter('name eq year'));
$year = intval($vars[0]['value']);

$vars = $vars_data_table->read(new \Data\Filter('name eq tcRegDates'));
$tcDates = $vars[0]['value'];
$tcStart = strtotime($tcDates['start']);
$tcEnd   = strtotime($tcDates['end'])+$secsInDay;
$tcRegClosed = $now < $tcStart || $now > $tcEnd;

$vars = $vars_data_table->read(new \Data\Filter('name eq artRegDates'));
$artDates = $vars[0]['value'];
$artStart = strtotime($artDates['start']);
$artEnd   = strtotime($artDates['end'])+$secsInDay;
$artRegClosed = $now < $artStart || $now > $artEnd;

$vars = $vars_data_table->read(new \Data\Filter('name eq dmvRegDates'));
$dmvDates = $vars[0]['value'];
$dmvStart = strtotime($dmvDates['start']);
$dmvEnd   = strtotime($dmvDates['end'])+$secsInDay;
$dmvRegClosed = $now < $dmvStart || $now > $dmvEnd;

$vars = $vars_data_table->read(new \Data\Filter('name eq eventRegDates'));
$evtDates = $vars[0]['value'];
$evtStart = strtotime($evtDates['start']);
$evtEnd   = strtotime($evtDates['end'])+$secsInDay;
$evtRegClosed = $now < $evtStart || $now > $evtEnd;

if($page->user)
{
    $camps_data_table = $data_set['camps'];
    $art_data_table = $data_set['art'];
    $dmv_data_table = $data_set['dmv'];
    $event_data_table = $data_set['event'];

    $filter = array('year'=>$year, 'registrars'=>$page->user->uid);

    $camps  = $camps_data_table->read($filter);
    $arts   = $art_data_table->read($filter);
    $cars   = $dmv_data_table->read($filter);
    $events = $event_data_table->read($filter);
}
$camps_count  = count($camps);
$arts_count   = count($arts);
$cars_count   = count($cars);
$events_count = count($events);



$manage_camp = '<a class="btn btn-secondary btn-lg" href="tc_reg.php">Register a Theme Camp</a>';
if($tcRegClosed)
{
    $manage_camp = '<a class="btn btn-secondary btn-lg disabled" href="#">Theme Camp Registration is Closed</a>';
}
if($camps_count > 0)
{
    $manage_camp.= '<ul>';
    for($i = 0; $i < $camps_count; $i++)
    {
        if($tcRegClosed)
        {
            $manage_camp.= '<li><a href="tc_reg.php?_id='.$camps[$i]['_id'].'">View your theme camp: '.$camps[$i]['name'].'</a></li>';
        }
        else
        {
            $manage_camp.= '<li><a href="tc_reg.php?_id='.$camps[$i]['_id'].'">Manage your theme camp: '.$camps[$i]['name'].'</a></li>';
        }
    }
    $manage_camp.= '</ul>';
}


$manage_art = '<a class="btn btn-secondary btn-lg" href="art_reg.php">Register an Art Project</a>';
if($artRegClosed)
{
    $manage_art = '<a class="btn btn-secondary btn-lg disabled" href="#">Art Registration is Closed</a>';
}
if($arts_count > 0)
{
    $manage_art.= '<ul>';
    for($i = 0; $i < $arts_count; $i++)
    {
       if($artRegClosed)
       {
           $manage_art.= '<li><a href="art_reg.php?_id='.$arts[$i]['_id'].'">View your art project: '.$arts[$i]['name'].'</a></li>';
       }
       else
       {
           $manage_art.= '<li><a href="art_reg.php?_id='.$arts[$i]['_id'].'">Manage your art project: '.$arts[$i]['name'].'</a></li>';
       }
    }
    $manage_art.= '</ul>';
}


$manage_car = '<a class="btn btn-secondary btn-lg" href="artCar_reg.php">Register an Art Car</a>';
if($dmvRegClosed)
{
    $manage_car = '<a class="btn btn-secondary btn-lg disabled" href="#">Art Car Registration is Closed</a>';
}
if($cars_count > 0)
{
    $manage_car.= '<ul>';
    for($i = 0; $i < $events_count; $i++)
    {
        if($dmvRegClosed)
        {
            $manage_car.= '<li><a href="artCar_reg.php?_id='.$cars[$i]['_id'].'">View your art car: '.$cars[$i]['name'].'</a></li>';
        }
        else
        {
            $manage_car.= '<li><a href="artCar_reg.php?_id='.$cars[$i]['_id'].'">Manage your art car: '.$cars[$i]['name'].'</a></li>';
        }
    }
    $manage_car.= '</ul>';
}


$manage_event = '<a class="btn btn-secondary btn-lg" href="event_reg.php">Register an Event</a>';
if($evtRegClosed)
{
    $manage_event = '<a class="btn btn-secondary btn-lg disabled" href="#">Event Registration is Closed</a>';
}
if($events_count > 0)
{
    $manage_event.= '<ul>';
    for($i = 0; $i < $events_count; $i++)
    {
        if($evtRegClosed)
        {
            $manage_event.= '<li><a href="event_reg.php?_id='.$events[$i]['_id'].'">View your event: '.$events[$i]['name'].'</a></li>';
        }
        else
        {
            $manage_event.= '<li><a href="event_reg.php?_id='.$events[$i]['_id'].'">Manage your event: '.$events[$i]['name'].'</a></li>';
        }
    }
    $manage_event.= '</ul>';
}

$page->body .= '
<div id="content">
    <h1>Welcome to the Burning Flipside Registration System</h1>
    <p></p>
    <h1>What would you like register?</h1>
        '.$manage_camp.'<div class="clearfix"></div>
        '.$manage_art.'<div class="clearfix"></div>
        '.$manage_car.'<div class="clearfix"></div>
        '.$manage_event.'
</div>';

$page->printPage();
