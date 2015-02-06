<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterWizardPage.php');
$page = new RegisterWizardPage('Art Car');

$index = $page->add_wizard_step('Basic Questions');
$page->add_form_group($index, 'Vehicle Owner\'s Name', 'vehicle_owner', 'text', 'This is the person who will be attending the event and be responsible for: any and all actions of vehicle, driver and passengers of mutant vehicle; and liability of said vehicle.', array('required'=>'true'));
$page->add_spacer($index);
$page->add_form_group($index, 'Email Address', 'vehicle_email', 'text', 'So we can get in touch with you.', array('required'=>'true'));
$page->add_spacer($index);
$page->add_form_group($index, 'Phone Number', 'vehicle_phone', 'text', 'So we can get in touch with you.');
$page->add_spacer($index);
$page->add_form_group($index, 'Theme Camp (if any)', 'vehicle_camp', 'text', 'So we can find you on site.');
$page->add_spacer($index);
$page->add_form_group($index, 'Vehicle Dimensions', 'vehicle_dimention', 'text', 'Our roads are narrow and pass under pecan trees. Let us know the height, width and length of your vehicle.', array('required'=>'true'));
$page->add_spacer($index);


$page->print_page();
?>
