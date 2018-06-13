<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.ViewListPage.php');
$page = new ViewListPage('Burning Flipside - Registration');

$page->content['endpoint'] = 'art';
$page->content['viewpage'] = 'view_art.php';

$page->printPage();
