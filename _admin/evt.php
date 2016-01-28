<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Events');

$page->add_js_from_src('//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js');
$page->add_css_from_src('//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css');
$page->add_js_from_src('js/bootbox.min.js');
$page->add_js_from_src('js/evt.js');

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
            <a href="https://secure.burningflipside.com/register/api/v1/event?fmt=xml">Data Dump Of Doom (.xml)</a> |
            <a href="https://secure.burningflipside.com/register/api/v1/event?fmt=csv">Spreadsheet (.csv)</a>
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
?>
