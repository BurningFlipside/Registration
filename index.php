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

$manage_add = '';
if(count($camps) > 0 || count($arts) > 0)
{
    $manage_add = '
        <li><a href="add.php">Manage Your Registrations</a></li>
    ';
}
else
{
    $manage_add = '<li><a href="add.php">Add a new registration</a></li>';
}

$page->body .= '
<div id="content">
    <h1>Welcome to the Burning Flipside Registration System</h1>
    <p></p>
    <h1>What would you like to do?</h1>
    <ul>
        <li><a href="view.php">View Existing Registrations</a></li>
        '.$manage_add.'
    </ul>
</div>';

$page->print_page();
?>
