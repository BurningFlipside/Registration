<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->addJS('js/view_obj.js');

$page->body .= '
<div id="content">
    <div class="row">
        <label for="name" class="col-sm-2 control-label">Art Project Name:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="name" id="name" readonly/>
        </div>
        <div class="w-100"></div>
        <label for="logo" class="col-sm-2 control-label">Art Project Logo:</label>
        <div class="col-sm-10" id="logo">
        </div>
        <div class="w-100"></div>
        <label for="site" class="col-sm-2 control-label">Art Project Website:</label>
        <div class="col-sm-10" id="site">
        </div>
        <div class="w-100"></div>
        <label for="teaser" class="col-sm-2 control-label">One Line Teaser:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="teaser" id="teaser" readonly/>
        </div>
        <div class="w-100"></div>
        <label for="description" class="col-sm-2 control-label">Description:</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="6" name="description" id="description" readonly></textarea>
        </div>
    </div>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_dialog">Contact the lead</button>
</div>
<div id="contact_dialog" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="contact-title">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="contact-title" class="modal-title">Contact Lead</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="_id" name="_id"/>
                <div class="form-group">
                     <label class="control-label" for="subject">Subject:</label>
                     <div class="clearfix visible-sm visible-md visible-lg"></div>
                     <input class="form-control" type="text" name="subject" id="subject"/>
                </div>
                <div class="clearfix visible-sm visible-md visible-lg"></div>
                <div class="form-group">
                    <label class="control-label" for="email_text">Content:</label>
                    <div class="clearfix visible-sm visible-md visible-lg"></div>
                    <textarea class="form-control" rows="6" name="email_text" id="email_text" style="margin: 0;"></textarea>
                </div>
                <div class="clearfix visible-sm visible-md visible-lg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="contact_btn" type="button" class="btn btn-primary" onclick="contact_lead();">Contact</button>
            </div>
        </div>
    </div>
</div>
';

$page->printPage();
