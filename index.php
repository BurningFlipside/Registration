<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterPage.php');
$page = new SecurePage('Burning Flipside - Registration');

$manage_add = '<li><a href="add.php">Add a new registration</a></li>';

if($page->user)
{
    $data_set = DataSetFactory::getDataSetByName('registration');
    $vars_data_table = $data_set['vars'];
    $camps_data_table = $data_set['camps'];
    $art_data_table = $data_set['art'];
    $dmv_data_table = $data_set['dmv'];
    $event_data_table = $data_set['event'];

    $vars = $vars_data_table->read(new \Data\Filter('name eq year'));
    $year = $vars[0]['value'];
    
    $filter = array('year'=>$year, 'registrars'=>$page->user->uid);

    $count = $camps_data_table->count($filter);
    if($count === 0)
    {
        $count = $art_data_table->count($filter);
        if($count === 0)
        {
            $count = $dmv_data_table->count($filter);
            if($count === 0)
            {
                $count = $event_data_table->count($filter);
            }
        }
    }
    if($count !== 0)
    {
        $manage_add = '<li><a href="add.php">Manage Your Registrations</a></li>';
    }
}

$page->body .= '
<div id="content">
    <h1>Welcome to the Burning Flipside Registration System</h1>
    <p></p>
    <h1>What would you like to do?</h1>
    <ul>
        <li><a href="view.php">View Existing Registrations</a></li>
        '.$manage_add.'
    </ul>
</div>';

$page->printPage();
