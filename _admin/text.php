<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration Text');

$page->addJS('//cdn.ckeditor.com/4.7.0/full/ckeditor.js', false);
$page->addJS('//cdn.ckeditor.com/4.7.0/full/adapters/jquery.js', false);

if(!$page->is_admin)
{
    $page->body .= '<div class="row"><h1>Not a registration admin!</h1>/div>';
}
else
{
    $page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Registration PDFs/Emails</h1>
            </div>
        </div>
        <div class="row">
    <div class="col-lg-12">
        <select id="registration_text_name" name="registration_text_name" class="form-control" onchange="registration_text_changed()">
            <option value="campPDF" selected>Camp One Page Print Out</option>
            <option value="campEmail">Camp Email "Receipt"</option>
            <option value="artEmail">Art Email "Receipt"</option>
        </select>
    </div>
</div>
<div class="row">
    <textarea id="pdf-source" style="width: 100%"></textarea>
</div>
<div class="row">
    <button onclick="save()">Save</button>
</div>
';
}

$page->printPage();
// vim: set tabstop=4 shiftwidth=4 expandtab:

