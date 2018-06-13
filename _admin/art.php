<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration');
$page->setTemplateName('@Register/reg-admin.html');

if(!$page->is_art_admin)
{
  $page->setTemplateName('admin.html');
  $page->body .= '<div class="row"><h1>Not an art admin!</h1>/div>';
}
else
{
    $page->content['title'] = 'Art Projects';
    $page->content['reports'] = array(
      array('link'=>'../api/v1/art?fmt=csv&filter=year eq current&no_logo', 'title'=>'Big Spreadsheet of Everything'),
      array('link'=>'../api/v1/art/*/artLead?fmt=csv&filter=year eq current', 'title'=>'Art Leads'),
      array('link'=>'../api/v1/art/*/soundLead?fmt=csv&filter=year eq current', 'title'=>'Sound Leads'),
      array('link'=>'../api/v1/art/*/safetyLead?fmt=csv&filter=year eq current', 'title'=>'Safety Leads'),
      array('link'=>'../api/v1/art/*/cleanupLead?fmt=csv&filter=year eq current', 'title'=>'Cleanup Leads')
    );
    $page->content['endpoint'] = 'art';
    $page->content['editUri'] = '../art_reg.php';
}

$page->printPage();
// vim: set tabstop=4 shiftwidth=4 expandtab:

