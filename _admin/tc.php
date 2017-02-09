<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->addWellKnownJS(JS_DATATABLE_ODATA);
$page->addWellKnownJS(JS_DATATABLE);
$page->addWellKnownCSS(CSS_DATATABLE);
$page->addWellKnownJS(JS_BOOTBOX);
$page->addJSByURI('js/tc.js');

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
            <a href="https://secure.burningflipside.com/register/api/v1/camps?fmt=xml&filter=year eq 2017">Data Dump Of Doom (.xml)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/campLead?fmt=csv&filter=year eq 2017">Camp Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/soundLead?fmt=csv&filter=year eq 2017">Sound Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/safetyLead?fmt=csv&filter=year eq 2017">Safety Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/volunteering?fmt=csv&filter=year eq 2017">Volunteering Leads (.csv)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/camps/*/cleanupLead?fmt=csv&filter=year eq 2017">Cleanup Leads (.csv)</a>
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

?>
