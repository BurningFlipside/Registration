<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration');
$page->setTemplateName('@Register/reg-admin.html');

if(!$page->is_dmv_admin)
{
    $page->setTemplateName('admin.html');
    $page->body .= '<div class="row"><h1>Not a DMV admin!</h1>/div>';
}
else
{
    $page->content['title'] = 'DMV';
    $page->content['reports'] = array(
      array('link'=>'../api/v1/dmv?fmt=csv&filter=year eq current&no_logo', 'title'=>'Big Spreadsheet of Everything')
    );
    $page->content['endpoint'] = 'dmv';
    $page->content['editUri'] = '../artCar_reg.php';
}

$page->printPage();
// vim: set tabstop=4 shiftwidth=4 expandtab:

