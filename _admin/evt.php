<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Events');
$page->setTemplateName('@Register/reg-admin.html');

if(!$page->is_event_admin)
{
    $page->setTemplateName('admin.html');
    $page->body .= '<div class="row"><h1>Not an Event admin!</h1>/div>';
}
else
{
    $page->content['title'] = 'Events';
    $page->content['reports'] = array(
      array('link'=>'../api/v1/event?fmt=csv&filter=year eq current&no_logo', 'title'=>'Big Spreadsheet of Everything'),
      array('link'=>'../api/v1/event?fmt=csv&filter=year eq current and Thursday eq true&no_logo', 'title'=>'Thursday'),
      array('link'=>'../api/v1/event?fmt=csv&filter=year eq current and Friday eq true&no_logo', 'title'=>'Friday'),
      array('link'=>'../api/v1/event?fmt=csv&filter=year eq current and Saturday eq true&no_logo', 'title'=>'Saturday'),
      array('link'=>'../api/v1/event?fmt=csv&filter=year eq current and Sunday eq true&no_logo', 'title'=>'Sunday'),
      array('link'=>'../api/v1/event?fmt=csv&filter=year eq current and Monday eq true&no_logo', 'title'=>'Monday')
    );
    $page->content['endpoint'] = 'event';
    $page->content['editUri'] = '../event_reg.php';
}

$page->printPage();
// vim: set tabstop=4 shiftwidth=4 expandtab:

