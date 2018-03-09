<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->addWellKnownJS(JS_DATATABLE);
$page->addWellKnownCSS(CSS_DATATABLE);
$page->addWellKnownJS(JS_BOOTBOX);
$page->addJSByURI('js/art.js');

if(!$page->is_art_admin)
{
    $page->body .= '<div class="row"><h1>Not an art admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Art Project</h1>
            </div>
        </div>
        <div class="row">
            <a class="dl_link" href="../api/v1/art?fmt=csv&filter=year eq 2018&no_logo">Big Spreadsheet of Everything</a> |
            <a class="dl_link" href="../api/v1/art/*/artLead?fmt=csv">Art Leads</a> |
            <a class="dl_link" href="../api/v1/art/*/soundLead?fmt=csv">Sound Leads</a> |
            <a class="dl_link" href="../api/v1/art/*/safetyLead?fmt=csv">Safety Leads</a> |
            <a class="dl_link" href="../api/v1/art/*/cleanupLead?fmt=csv">Cleanup Leads</a>
            Format Preference: <select id="dlFormat" onChange="changeDLType()">
                <option value="csv" selected="selected">Comma Sepearated Value (.csv)</option>
                <option value="xls">Excel 97-2003 (.xls)</option>
                <option value="xlsx">Excel Workbook (.xlsx)</option>
            </select>
        </div>
        <div class="row">
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#coltoggle" aria-expanded="false" aria-controls="coltoggle">
                Toggle Columns
            </button>
            <div class="collapse" id="coltoggle"></div>
            <table id="art" class="table">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
';
}

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:

