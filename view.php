<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->body .= '<ul>
  <li><a href="view_tcs.php">View Registered Theme Camps</a></li>
  <li><a href="view_arts.php">View Registered Art Projects</a></li>
  <li><a href="view_dmvs.php">View Registered Art Cars</a></li>
  <li><a href="view_events.php">View Registered Events</a></li>
</ul>';

$page->printPage();
