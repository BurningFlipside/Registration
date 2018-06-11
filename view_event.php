<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->addJS('js/view_obj.js');

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
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="Thursday" class="col-sm-2 control-label">Thursday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Thursday" id="Thursday" readonly disabled/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="Friday" class="col-sm-2 control-label">Friday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Friday" id="Friday" readonly disabled/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="Saturday" class="col-sm-2 control-label">Saturday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Saturday" id="Saturday" readonly disabled/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="Sunday" class="col-sm-2 control-label">Sunday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Sunday" id="Sunday" readonly disabled/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="Monday" class="col-sm-2 control-label">Monday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Monday" id="Monday" readonly disabled/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="start" class="col-sm-2 control-label">Start:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="start" id="start" readonly/>
        </div>
    </div>
    <div class="clearfix visible-sm visible-md visible-lg"></div>
    <div class="form-group">
        <label for="end" class="col-sm-2 control-label">End:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="end" id="end" readonly/>
        </div>
    </div>
</div>
';

$page->printPage();
