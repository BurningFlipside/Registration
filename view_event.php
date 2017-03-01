<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->addJSByURI('js/view_obj.js');

$page->body .= '
<div id="content">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Event Name:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="name" id="name" readonly/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="logo" class="col-sm-2 control-label">Event Logo:</label>
        <div class="col-sm-10" id="logo">
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="site" class="col-sm-2 control-label">Event Website:</label>
        <div class="col-sm-10" id="site">
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="teaser" class="col-sm-2 control-label">One Line Teaser:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="teaser" id="teaser" readonly/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">Description:</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="6" name="description" id="description" readonly></textarea>
        </div>
    </div>
</div>
';

$page->printPage();
