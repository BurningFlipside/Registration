<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->addWellKnownJS(JS_DATATABLE_ODATA, false);
$page->addWellKnownJS(JS_DATATABLE, false);
$page->addWellKnownCSS(CSS_DATATABLE);
$page->addWellKnownJS(JS_BOOTBOX);
$page->addJSByURI('js/tc.js', false);

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
            <a class="dl_link" href="../api/v1/camps?fmt=csv&filter=year eq 2018&no_logo">Big Spreadsheet of Everything</a> |
            <a class="dl_link" href="../api/v1/camps/*/campLead?fmt=csv&filter=year eq 2018">Camp Leads</a> |
            <a class="dl_link" href="../api/v1/camps/*/soundLead?fmt=csv&filter=year eq 2018">Sound Leads</a> |
            <a class="dl_link" href="../api/v1/camps/*/safetyLead?fmt=csv&filter=year eq 2018">Safety Leads</a> |
            <a class="dl_link" href="../api/v1/camps/*/volunteering?fmt=csv&filter=year eq 2018">Volunteering Leads</a> |
            <a class="dl_link" href="../api/v1/camps/*/cleanupLead?fmt=csv&filter=year eq 2018">Cleanup Leads</a> |
            <a class="dl_link" href="../api/v1/camps/*/doStructView?fmt=csv&filter=year eq 2018">Structs</a>
            Format Preference: <select id="dlFormat" onChange="changeDLType()">
                <option value="csv" selected="selected">Comma Sepearated Value (.csv)</option>
                <option value="xls">Excel 97-2003 (.xls)</option>
                <option value="xlsx">Excel Workbook (.xlsx)</option>
            </select>
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
