<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('Autoload.php');
require('class.RegisterAPI.php');
require('class.VariablesAPI.php');
require('class.TextAPI.php');
require('class.RegistrationAPI.php');
require('class.CampRegistrationPDF.php');

$site = new \Flipside\Http\WebSite();
$site->registerAPI('/', new RegisterAPI());
$site->registerAPI('/vars', new VariablesAPI());
$site->registerAPI('/art', new RegistrationAPI('art', 'ArtAdmins'));
$site->registerAPI('/camps', new RegistrationAPI('camps', 'CampAdmins', '\CampRegistrationPDF', '\CampRegistrationEmail'));
$site->registerAPI('/dmv', new RegistrationAPI('dmv', 'DMVAdmins'));
$site->registerAPI('/event', new RegistrationAPI('event', 'EventAdmins'));
$site->registerAPI('/text', new TextAPI());
$site->run();
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
