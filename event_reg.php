<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterWizardPage.php');
$page = new RegisterWizardPage('Event');

$index = $page->add_wizard_step('Basic Questions');
$page->add_form_group($index, 'Event Host', 'host', 'text', 'The camp, art project, or person who is hosting the event.', array('required'=>'true'));

$page->add_form_group($index, 'Event Location', 'location', 'text', 'The camp, art project, or other location at which this event will be held.', array('required'=>'true'));


$index = $page->add_wizard_step('Day and Time');
$days = array(
    array('id'=>'Thursday', 'name'=>'Thursday May 25, 2017'),
    array('id'=>'Friday', 'name'=>'Friday May 26, 2017'),
    array('id'=>'Saturday', 'name'=>'Saturday May 27, 2017'),
    array('id'=>'Sunday', 'name'=>'Sunday May 28, 2017'),
    array('id'=>'Monday', 'name'=>'Monday May 29, 2017')
);
$day_count = count($days);
for($i = 0; $i < $day_count; $i++)
{
    $page->add_form_group($index, $days[$i]['name'], $days[$i]['id'], 'checkbox');
    
}

$options = array(
    array('value'=>'all', 'text'=>'All Day', 'selected'=>TRUE),
    array('value'=>'0100', 'text'=>'1 AM'),
    array('value'=>'0200', 'text'=>'2 AM'),
    array('value'=>'0300', 'text'=>'3 AM'),
    array('value'=>'0400', 'text'=>'4 AM'),
    array('value'=>'0500', 'text'=>'5 AM'),
    array('value'=>'0600', 'text'=>'6 AM'),
    array('value'=>'0700', 'text'=>'7 AM'),
    array('value'=>'0800', 'text'=>'8 AM'),
    array('value'=>'0900', 'text'=>'9 AM'),
    array('value'=>'1000', 'text'=>'10 AM'),
    array('value'=>'1100', 'text'=>'11 AM'),
    array('value'=>'1200', 'text'=>'Noon'),
    array('value'=>'1300', 'text'=>'1 PM'),
    array('value'=>'1400', 'text'=>'2 PM'),
    array('value'=>'1500', 'text'=>'3 PM'),
    array('value'=>'1600', 'text'=>'4 PM'),
    array('value'=>'1700', 'text'=>'5 PM'),
    array('value'=>'1800', 'text'=>'6 PM'),
    array('value'=>'1900', 'text'=>'7 PM'),
    array('value'=>'2000', 'text'=>'8 PM'),
    array('value'=>'2030', 'text'=>'Dusk'),
    array('value'=>'2100', 'text'=>'9 PM'),
    array('value'=>'2200', 'text'=>'10 PM'),
    array('value'=>'2300', 'text'=>'11 PM'),
    array('value'=>'0000', 'text'=>'Midnight'),
    array('value'=>'?', 'text'=>'Until we run out of stuff or are tired!')
);
$page->add_form_group($index, 'Start Time:', 'start', 'select', 'When your event will start.', array('options'=>$options));

$page->add_form_group($index, 'End Time:', 'end', 'select', 'When your event will end.', array('options'=>$options));


$index = $page->add_wizard_step('Event Type');
$types = array("Amenities and Services", "Art Installation", "Costumed or Themed Event", "Dance Party", "Flash Mob or Rampage", "Food Event", "Game, Contest or Competition",
               "General Shenanigans", "Interactive Display", "Kiki, We Wanna Have a Kiki", "Live Music", "Musical Event", "Participatory Art Project", "Skills Demonstration",
               "Social Interaction");
$type_count = count($types);
for($i = 0; $i < $type_count; $i++)
{
    $page->add_form_group($index, $types[$i], $types[$i], 'checkbox');
    
}

$page->printPage();
?>
