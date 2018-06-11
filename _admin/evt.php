<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Events');

$page->addWellKnownJS(JS_DATATABLE, false);
$page->addWellKnownCSS(CSS_DATATABLE, false);
$page->addWellKnownJS(JS_BOOTBOX);
$page->addJSByURI('js/evt.js');

if(!$page->is_event_admin)
{
    $page->body .= '<div class="row"><h1>Not an Event admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Events</h1>
            </div>
        </div>
        <div class="row">
            <a class="dl_link" href="../api/v1/event?fmt=csv">Data Dump Of Doom</a> |
            <a class="dl_link" href="../api/v1/event?no_logo=1&fmt=csv">No logos</a> |
            <a class="dl_link" href="../api/v1/event?no_logo=1&$filter=year eq current and Thursday eq true&fmt=csv">Thursday</a> |
            <a class="dl_link" href="../api/v1/event?no_logo=1&$filter=year eq current and Friday eq true&fmt=csv">Friday</a> |
            <a class="dl_link" href="../api/v1/event?no_logo=1&$filter=year eq current and Saturday eq true&fmt=csv">Saturday</a> |
            <a class="dl_link" href="../api/v1/event?no_logo=1&$filter=year eq current and Sunday eq true&fmt=csv">Sunday</a> |
            <a class="dl_link" href="../api/v1/event?no_logo=1&$filter=year eq current and Monday eq true&fmt=csv">Monday</a>
            Format Preference: <select id="dlFormat" onChange="changeDLType()">
                <option value="csv" selected="selected">Comma Sepearated Value (.csv)</option>
                <option value="xls">Excel 97-2003 (.xls)</option>
                <option value="xlsx">Excel Workbook (.xlsx)</option>
            </select>
        </div>
        <div class="row">
            <table id="evt" class="table">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
';
}

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:

