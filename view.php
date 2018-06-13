<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->addWellKnownJS(JS_DATATABLE, false);
$page->addWellKnownJS(JS_DATATABLE_ODATA, false);
$page->addWellKnownCSS(CSS_DATATABLE);

$page->body .= '<ul>
  <li><a href="view_tcs.php">View Registered Theme Camps</a></li>
  <li><a href="view_arts.php">View Registered Art Projects</a></li>
  <li><a href="view_dmvs.php">View Registered Art Cars</a></li>
  <li><a href="view_events.php">View Registered Events</a></li>
</ul>';
/*
$page->body .= '
    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="card">
            <div class="card-header" role="tab" id="themeCampHeader">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#tc" aria-expanded="true" aria-controls="tc">Theme Camps</a>
                </h4>
            </div>
            <div id="tc" class="collapse show" role="tabpanel" aria-labelledby="themeCampHeader">
                <div class="card-body">
                    <table class="table" id="tcTable">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Teaser</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </idv>
        </div>
        <div class="card">
            <div class="card-header" role="tab" id="artHeader">
                <h4 class="card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#art" aria-expanded="true" aria-controls="art">Art Project</a>
                </h4>
            </div>
            <div id="art" class="collapse" role="tabpanel" aria-labelledby="artHeader">
                <div class="card-body">
                    <table class="table" id="artTable">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Teaser</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" id="dmvHeader">
                <h4 class="card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#dmv" aria-expanded="true" aria-controls="dmv">Art Cars</a>
                </h4>
            </div>
            <div id="dmv" class="collapse" role="tabpanel" aria-labelledby="dmvHeader">
                <div class="card-body">
                    <table class="table" id="dmvTable">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Teaser</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" role="tab" id="eventHeader">
                <h4 class="card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#event" aria-expanded="true" aria-controls="event">Events</a>
                </h4>
            </div>
            <div id="event" class="collapse" role="tabpanel" aria-labelledby="eventHeader">
                <div class="card-body">
                    <table class="table" id="eventTable">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Teaser</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
';*/

$page->printPage();
