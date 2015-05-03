<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
require_once('class.RegistrationDB.php');
$page = new RegisterPage('Burning Flipside - Registration');

$page->add_js(JS_DATATABLE_ODATA);
$page->add_js(JS_DATATABLE);
$page->add_css_from_src('//cdn.datatables.net/1.10.5/css/jquery.dataTables.min.css');
$page->add_js_from_src('js/view.js');

if(!FlipSession::is_logged_in())
{
$page->body .= '
    <div id="content">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">You must <a href="https://profiles.burningflipside.com/login.php?return='.$page->current_url().'">log in <span class="glyphicon glyphicon-log-in"></span></a> to access the Burning Flipside Registration system!</h1>
            </div>
        </div>
    </div>
';
}
else
{
$page->body .= '
<div id="content">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="themeCampHeader">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#tc" aria-expanded="true" aria-controls="tc">Theme Camps</a>
                </h4>
            </div>
            <div id="tc" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="themeCampHeader">
                <div class="panel-body">
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
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="artHeader">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#art" aria-expanded="true" aria-controls="art">Art Project</a>
                </h4>
            </div>
            <div id="art" class="panel-collapse collapse" role="tabpanel" aria-labelledby="artHeader">
                <div class="panel-body">
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
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="dmvHeader">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#dmv" aria-expanded="true" aria-controls="dmv">Art Cars</a>
                </h4>
            </div>
            <div id="dmv" class="panel-collapse collapse" role="tabpanel" aria-labelledby="dmvHeader">
                <div class="panel-body">
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
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="eventHeader">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#event" aria-expanded="true" aria-controls="event">Events</a>
                </h4>
            </div>
            <div id="event" class="panel-collapse collapse" role="tabpanel" aria-labelledby="eventHeader">
                <div class="panel-body">
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
</div>';
}

$page->print_page();
?>
