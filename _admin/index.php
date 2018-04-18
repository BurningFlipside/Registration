<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Tickets');

$page->addJSByURI('js/index.js');

$data_set = DataSetFactory::getDataSetByName('registration');
$vars_data_table = $data_set['vars'];
$camps_data_table = $data_set['camps'];
$art_data_table = $data_set['art'];
$dmv_data_table = $data_set['dmv'];
$event_data_table = $data_set['event'];

$vars = $vars_data_table->read(new \Data\Filter('name eq year'));
$year = $vars[0]['value'];

//$filter = array('year'=>$year);
$filter = new \Data\Filter('year eq '.$year);

$camp_count = $camps_data_table->count($filter);
$art_count = $art_data_table->count($filter);
$dmv_count = $dmv_data_table->count($filter);
$event_count = $event_data_table->count($filter);

$filter = new \Data\Filter('final eq true and year eq '.$year);

$done_camp_count = $camps_data_table->count($filter);

$filter = new \Data\Filter('final eq true and year eq '.$year);
$done_art_count = $art_data_table->count($filter);

$page->body .= '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
        </div>
        <div class="row">';
if($page->is_tc_admin)
{
    $page->add_card('fa-bed', $camp_count, 'Theme Camps', 'tc.php');
}
if($page->is_art_admin)
{
    $page->add_card('fa-picture-o', $art_count, 'Art Projects', 'art.php', $page::CARD_GREEN); 
}
if($page->is_dmv_admin)
{
    $page->add_card('fa-car', $dmv_count, 'Art Cars', 'dmv.php', $page::CARD_YELLOW);
}
if($page->is_event_admin)
{
    $page->add_card('fa-calendar', $event_count, 'Events', 'evt.php', $page::CARD_RED);
}

$page->body .= '</div><div class="row">';

if($page->is_tc_admin)
{
    $page->add_card('fa-check', $done_camp_count, 'Finished Theme Camps', 'tc.php?finished=true', $page::CARD_RED);
}
if($page->is_art_admin)
{
    $page->add_card('fa-check', $done_art_count, 'Finished Art Projects', 'art.php?finished=true', $page::CARD_YELLOW);
}

$page->body .= '</div></div></div>';

$page->print_page();
// vim: set tabstop=4 shiftwidth=4 expandtab:

