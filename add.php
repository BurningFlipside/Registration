<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.SecurePage.php');
require_once('class.RegistrationDB.php');
$page = new SecurePage('Burning Flipside - Registration');

$db = new RegistrationDB();
$user = FlipSession::get_user();
if($user)
{
    $camps  = $db->getAllThemeCampsForUser($user->uid[0]);
    $arts   = $db->getAllArtProjectsForUser($user->uid[0]);
    $cars   = $db->getAllArtCarsForUser($user->uid[0]);
    $events = $db->getAllEventsForUser($user->uid[0]);
}
else
{
    $camps  = array();
    $arts   = array();
    $cars   = array();
    $events = array();
}

$manage_camp = '<li><a href="tc_reg.php">Register a theme camp</a></li>';
if(count($camps) > 0)
{
    $manage_camp = '<li><a href="tc_reg.php?_id='.$camps[0]['_id'].'">Manage your theme camp</a></li>';
}
$manage_art = '<li><a href="art_reg.php">Register a new art project</a></li>';
$art_count = count($arts);
if($art_count == 1)
{
    $manage_art.= '<li><a href="art_reg.php?_id='.$arts[0]['_id'].'">Manage your art project</a></li>';
}
else if($art_count >= 1)
{
    $manage_art.= '<ul>';
    for($i = 0; $i < $art_count; $i++)
    {
       $manage_art.= '<li><a href="art_reg.php?_id='.$arts[$i]['_id'].'">Manage your art project: '.$arts[$i]['name'].'</a></li>';
    }
    $manage_art.= '</ul>';
}
$manage_car = '<li><a href="artCar_reg.php">Register an art car</a></li>';
if(count($cars))
{
    $manage_car = '<li><a href="artCar_reg.php?_id='.$cars[0]['_id'].'">Manage your art car</a></li>';
}
$manage_event = '<li><a href="event_reg.php">Register a new event</a></li>';
$event_count = count($events);
if($event_count == 1)
{
    $manage_event.= '<li><a href="event_reg.php?_id='.$events[0]['_id'].'">Manage your event</a></li>';
}
else if($event_count >= 1)
{
    $manage_event.= '<ul>';
    for($i = 0; $i < $event_count; $i++)
    {
       $manage_event.= '<li><a href="event_reg.php?_id='.$events[$i]['_id'].'">Manage your event: '.$events[$i]['name'].'</a></li>'; 
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
