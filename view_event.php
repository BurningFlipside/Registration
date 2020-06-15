<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->addJS('js/view_obj.js');

$page->body .= '
<div id="content">
    <div class="row">
        <label for="name" class="col-sm-2 control-label">Event Name:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="name" id="name" readonly/>
        </div>
        <div class="w-100"></div>
        <label for="logo" class="col-sm-2 control-label">Event Logo:</label>
        <div class="col-sm-10" id="logo">
        </div>
        <div class="w-100"></div>
        <label for="site" class="col-sm-2 control-label">Event Website:</label>
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
        <div class="w-100"></div>
        <label for="Thursday" class="col-sm-2 control-label">Thursday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Thursday" id="Thursday" readonly disabled/>
        </div>
        <div class="w-100"></div>
        <label for="Friday" class="col-sm-2 control-label">Friday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Friday" id="Friday" readonly disabled/>
        </div>
        <div class="w-100"></div>
        <label for="Saturday" class="col-sm-2 control-label">Saturday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Saturday" id="Saturday" readonly disabled/>
        </div>
        <div class="w-100"></div>
        <label for="Sunday" class="col-sm-2 control-label">Sunday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Sunday" id="Sunday" readonly disabled/>
        </div>
        <div class="w-100"></div>
        <label for="Monday" class="col-sm-2 control-label">Monday:</label>
        <div class="col-sm-10">
            <input class="form-control" type="checkbox" name="Monday" id="Monday" readonly disabled/>
        </div>
        <div class="w-100"></div>
        <label for="start" class="col-sm-2 control-label">Start:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="start" id="start" readonly/>
        </div>
        <div class="w-100"></div>
        <label for="end" class="col-sm-2 control-label">End:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="end" id="end" readonly/>
        </div>
        <label for="location" class="col-sm-2 control-label">Location:</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" name="location" id="location" readonly/>
        </div>
    </div>
</div>
';

$page->printPage();
