<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->add_js_from_src('//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js');
$page->add_css_from_src('//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css');
$page->add_js_from_src('js/bootbox.min.js');
$page->add_js_from_src('js/tc.js');

if(!$page->is_tc_admin)
{
    $page->body .= '<div class="row"><h1>Not a theme camp admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Theme Camps</h1>
            </div>
        </div>
        <div class="row">
            <a href="https://secure.burningflipside.com/register/api/v1/camps?fmt=xml">Data Dump Of Doom (.xml)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/campLead?fmt=csv">Camp Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/soundLead?fmt=csv">Sound Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/safetyLead?fmt=csv">Safety Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/volunteering?fmt=csv">Volunteering Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/cleanupLead?fmt=csv">Cleanup Leads (.csv)</a>
        </div>
        <div class="row">
            <table id="tc" class="table">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
';
}

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:
?>

