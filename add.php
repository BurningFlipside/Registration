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
    $camps = $db->getAllThemeCampsForUser($user->uid[0]);
    $arts  = $db->getAllArtProjectsForUser($user->uid[0]);
}
else
{
    $camps = array();
    $arts  = array();
}

$manage_camp = '<li><a href="tc_reg.php">Register a theme camp</a></li>';
if(count($camps) > 0)
{
    $manage_camp = '<li><a href="tc_reg.php?_id='.$camps[0]['_id'].'">Manage your theme camp</a></li>';
}
$manage_art = '<li><a href="art_reg.php">Register an art project</a></li>';
if(count($arts) > 0)
{
    $manage_art = '<li><a href="art_reg.php?_id='.$arts[0]['_id'].'">Manage your art project</a></li>';
}

$page->body .= '
<div id="content">
    <h1>Welcome to the Burning Flipside Registration System</h1>
    <p></p>
    <h1>What would you like register?</h1>
    <ul>
        '.$manage_camp.'
        '.$manage_art.'
        <li><a href="artCar_reg.php">Register an art car</a></li>
        <li><a href="event_reg.php">Register an event</a></li>
    </ul>
</div>';

$page->print_page();
?>
