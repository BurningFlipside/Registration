<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterAdminPage.php');
$page = new RegisterAdminPage('Burning Flipside - Registration');
$page->setTemplateName('admin-dashboard.html');

if($page->is_tc_admin)
{
    $page->addCard('fa-bed', '<span id="tcCount">?</span> Theme Camps', 'tc.php');
}
if($page->is_art_admin)
{
    $page->addCard('fa-palette', '<span id="artCount">?</span> Art Projects', 'art.php', $page::CARD_GREEN); 
}
if($page->is_dmv_admin)
{
    $page->addCard('fa-car', '<span id="dmvCount">?</span> Art Cars', 'dmv.php', $page::CARD_YELLOW);
}
if($page->is_event_admin)
{
    $page->addCard('fa-calendar', '<span id="eventCount">?</span> Events', 'evt.php', $page::CARD_RED);
}
if($page->is_tc_admin)
{
    $page->addCard('fa-check', '<span id="tcDoneCount">?</span> Finished Theme Camps', 'tc.php?finished=true', $page::CARD_RED);
}
if($page->is_art_admin)
{
    $page->addCard('fa-check', '<span id="artDoneCount">?</span> Finished Art Projects', 'art.php?finished=true', $page::CARD_YELLOW);
}

$page->printPage();
// vim: set tabstop=4 shiftwidth=4 expandtab:

