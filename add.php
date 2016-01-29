<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.SecurePage.php');
$page = new SecurePage('Burning Flipside - Registration');

$camps    = array();
$arts     = array();
$cars     = array();
$events   = array();
$tcDates  = array();
$artDates = array();
$dmvDates = array();
$evtDates = array();

if($page->user)
{
    $data_set = DataSetFactory::get_data_set('registration');
    $vars_data_table = $data_set['vars'];
    $camps_data_table = $data_set['camps'];
    $art_data_table = $data_set['art'];
    $dmv_data_table = $data_set['dmv'];
    $event_data_table = $data_set['event'];

    $vars = $vars_data_table->read(new \Data\Filter('name eq year'));
    $year = $vars[0]['value'];

    $vars = $vars_data_table->read(new \Data\Filter('name eq tcRegDates'));
    $tcDates = $vars[0]['value'];

    $vars = $vars_data_table->read(new \Data\Filter('name eq artRegDates'));
    $artDates = $vars[0]['value'];

    $vars = $vars_data_table->read(new \Data\Filter('name eq dmvRegDates'));
    $dmvDates = $vars[0]['value'];

    $vars = $vars_data_table->read(new \Data\Filter('name eq eventRegDates'));
    $evtDates = $vars[0]['value'];

    $filter = array('year'=>$year, 'registrars'=>$page->user->getUid());

    $camps  = $camps_data_table->read($filter);
    $arts   = $art_data_table->read($filter);
    $cars   = $dmv_data_table->read($filter);
    $events = $event_data_table->read($filter);
}
$camps_count  = count($camps);
$arts_count   = count($arts);
$cars_count   = count($cars);
$events_count = count($events);

$now = getdate();
$tcStart = date_parse($tcDates['start']);
$tcEnd   = date_parse($tcDates['end']);

$manage_camp = '<li><a href="tc_reg.php">Register a new theme camp</a></li>';
if($now < $tcStart || $now > $tcEnd)
{
    $manage_camp = '<li>Theme Camp Registration is Closed</li>';
}
if($camps_count > 0)
{
    for($i = 0; $i < $camps_count; $i++)
    {
        if($now < $tcStart || $now > $tcEnd)
        {
            $manage_camp.= '<li><a href="tc_reg.php?_id='.$camps[$i]['_id'].'">View your theme camp: '.$camps[$i]['name'].'</a></li>';
        }
        else
        { 
            $manage_camp.= '<li><a href="tc_reg.php?_id='.$camps[$i]['_id'].'">Manage your theme camp: '.$camps[$i]['name'].'</a></li>';
        }
    }
}

$artStart = date_parse($artDates['start']);
$artEnd   = date_parse($artDates['end']);

$manage_art = '<li><a href="art_reg.php">Register a new art project</a></li>';
if($now < $artStart || $now > $artEnd)
{
    $manage_art = '<li>Art Registration is Closed</li>';
}
if($arts_count > 0)
{
    $manage_art.= '<ul>';
    for($i = 0; $i < $arts_count; $i++)
    {
       if($now < $artStart || $now > $artEnd)
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

$dmvStart = date_parse($dmvDates['start']);
$dmvEnd   = date_parse($dmvDates['end']);

$manage_car = '<li><a href="artCar_reg.php">Register a new art car</a></li>';
if($now < $dmvStart || $now > $dmvEnd)
{
    $manage_car = '<li>Art Car Registration is Closed</li>';
}
if($cars_count > 0)
{
    $manage_car.= '<ul>';
    for($i = 0; $i < $events_count; $i++)
    {
        if($now < $dmvStart || $now > $dmvEnd)
        {
            $manage_car = '<li><a href="artCar_reg.php?_id='.$cars[$i]['_id'].'">View your art car: '.$cars[$i]['name'].'</a></li>';
        }
        else
        {
            $manage_car = '<li><a href="artCar_reg.php?_id='.$cars[$i]['_id'].'">Manage your art car: '.$cars[$i]['name'].'</a></li>';
        }
    }
    $manage_car.= '</ul>';
}

$evtStart = date_parse($evtDates['start']);
$evtEnd   = date_parse($evtDates['end']);

$manage_event = '<li><a href="event_reg.php">Register a new event</a></li>';
if($now < $evtStart || $now > $evtEnd)
{
    $manage_event = '<li>Event Registration is Closed</li>';
}
if($events_count > 0)
{
    $manage_event.= '<ul>';
    for($i = 0; $i < $events_count; $i++)
    {
        if($now < $evtStart || $now > $evtEnd)
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
    <ul>
        '.$manage_camp.'
        '.$manage_art.'
        '.$manage_car.'
        '.$manage_event.'
    </ul>
</div>';

$page->print_page();
?>
