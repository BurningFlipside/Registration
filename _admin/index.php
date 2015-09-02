<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
require_once('class.RegistrationDB.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->add_js_from_src('js/index.js');

$data_set = DataSetFactory::get_data_set('registration');
$vars_data_table = $data_set['vars'];
$camps_data_table = $data_set['camps'];
$art_data_table = $data_set['art'];
$dmv_data_table = $data_set['dmv'];
$event_data_table = $data_set['event'];

$vars = $vars_data_table->read(new \Data\Filter('name eq year'));
$year = $vars[0]['value'];

$filter = array('year'=>$year);

$camp_count = $camps_data_table->count($filter);
$art_count = $art_data_table->count($filter);
$dmv_count = $dmv_data_table->count($filter);
$event_count = $event_data_table->count($filter);

$tc  = '';
$art = '';
$dmv = '';
$evt = '';
if($page->is_tc_admin)
{
    $tc = '<div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="glyphicon glyphicon-tent" style="font-size: 5em;"></span>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size: 40px;">'.$camp_count.'</div>
                                <div>Theme Camps</div>
                            </div>
                        </div>
                    </div>
                    <a href="tc.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right glyphicon glyphicon-circle-arrow-right"></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>';
}
if($page->is_art_admin)
{
    $art = '<div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="glyphicon glyphicon-blackboard" style="font-size: 5em;"></span>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size: 40px;">'.$art_count.'</div>
                                <div>Art Projects</div>
                            </div>
                        </div>
                    </div>
                    <a href="art.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right glyphicon glyphicon-circle-arrow-right"></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>';
}
if($page->is_dmv_admin)
{
    $dmv = '<div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="glyphicon glyphicon-road" style="font-size: 5em;"></span>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size: 40px;">'.$dmv_count.'</div>
                                <div>Art Cars</div>
                            </div>
                        </div>
                    </div>
                    <a href="dmv.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right glyphicon glyphicon-circle-arrow-right"></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>';
}
if($page->is_event_admin)
{
    $evt = '<div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <span class="glyphicon glyphicon-glass" style="font-size: 5em;"></span>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div style="font-size: 40px;">'.$event_count.'</div>
                                <div>Events</div>
                            </div>
                        </div>
                    </div>
                    <a href="evt.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right glyphicon glyphicon-circle-arrow-right"></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>';
}

$page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
        </div>
        <div class="row">'.$tc.$art.$dmv.$evt.'
        </div>
    </div>
</div>
';

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:
?>

