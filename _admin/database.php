<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration DB');

if(!$page->is_admin)
{
    $page->body .= '<div class="row"><h1>Not a registration admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Registration DB Ops</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2">
                <button type="button" class="btn btn-primary" onclick="compress();">Compress Images</button>
            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-danger" id="removeBttn">Remove Old Images</button>
            </div>
        </div>
';
}

$page->printPage();
// vim: set tabstop=4 shiftwidth=4 expandtab:

