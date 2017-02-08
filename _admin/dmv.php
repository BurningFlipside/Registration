<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->addWellKnownJS(JS_DATATABLE);
$page->addWellKnownCSS(CSS_DATATABLE);
$page->addWellKnownJS(JS_BOOTBOX);
$page->addJSByURI('js/dmv.js');

if(!$page->is_dmv_admin)
{
    $page->body .= '<div class="row"><h1>Not a DMV admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">DMV</h1>
            </div>
        </div>
        <div class="row">
            <a href="https://secure.burningflipside.com/register/api/v1/dmv?fmt=xml">Data Dump Of Doom (.xml)</a>
        </div>
        <div class="row">
            <table id="dmv" class="table">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
';
}

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:

