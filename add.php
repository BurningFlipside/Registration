<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.SecurePage.php');
require_once('class.RegistrationDB.php');
$page = new SecurePage('Burning Flipside - Registration');

$page->body .= '
<div id="content">
    <h1>Welcome to the Burning Flipside Registration System</h1>
    <p></p>
    <h1>What would you like register?</h1>
    <ul>
        <li><a href="tc_reg.php">Register a theme camp</a></li>
        <li><a href="art_reg.php">Register an art project</a></li>
        <li><a href="artCar_reg.php">Register an art car</a></li>
        <li><a href="event_reg.php">Register an event</a></li>
    </ul>
</div>';

$page->print_page();
?>
