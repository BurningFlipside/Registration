<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.ViewListPage.php');
$page = new ViewListPage('Burning Flipside - Registration');
$page->setTemplateName('@Register/view-event-list.html');

$page->printPage();
