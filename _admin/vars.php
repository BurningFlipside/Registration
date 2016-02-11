<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration Variables');

$page->add_js_from_src('js/bootbox.min.js');
$page->add_js_from_src('js/vars.js');

if(!$page->is_admin)
{
    $page->body .= '<div class="row"><h1>Not a registration admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Registration Variables</h1>
            </div>
        </div>
        <div class="row">
            <table id="vars" class="table">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>
';
}

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:
?>

