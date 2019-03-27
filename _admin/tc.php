<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration');
$page->setTemplateName('@Register/reg-admin.html');

if(!$page->is_tc_admin)
{
    $page->setTemplateName('admin.html');
    $page->body .= '<div class="row"><h1>Not a theme camp admin!</h1>/div>';
}
else
{
    $page->content['title'] = 'Theme Camps';
    $page->content['reports'] = array(
      array('link'=>'../api/v1/camps?fmt=csv&filter=year eq current&no_logo', 'title'=>'Big Spreadsheet of Everything'),
      array('link'=>'../api/v1/camps/*/campLead?fmt=csv&filter=year eq current', 'title'=>'Camp Leads'),
      array('link'=>'../api/v1/camps/*/soundLead?fmt=csv&filter=year eq current', 'title'=>'Sound Leads'),
      array('link'=>'../api/v1/camps/*/safetyLead?fmt=csv&filter=year eq current', 'title'=>'Safety Leads'),
      array('link'=>'../api/v1/camps/*/volunteering?fmt=csv&filter=year eq current', 'title'=>'Volunteering Leads'),
      array('link'=>'../api/v1/camps/*/cleanupLead?fmt=csv&filter=year eq current', 'title'=>'Cleanup Leads'),
      array('link'=>'../api/v1/camps/*/doStructView?fmt=csv&filter=year eq current', 'title'=>'Structs'),
      array('link'=>'../api/v1/camps?fmt=csv&$filter=earlyArrival.bool eq true and year eq current', 'title'=>'Early Arrival')
    );
    $page->content['othertop'] = '<a href="../api/v1/camps?$format=text/html">One Page Per Camp</a>';
    $page->content['endpoint'] = 'camps';
    $page->content['editUri'] = '../tc_reg.php';
}

$page->printPage();

?>
