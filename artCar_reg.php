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
$page->add_form_group($index, 'Number of Passengers', 'vehicle_passengers', 'text', 'The number of passengers you can comfortably carry.', array('required'=>'true'));
$page->add_spacer($index);
$page->add_form_group($index, 'This art car has sound', 'has_sound', 'checkbox', 'This art car will utilize amplified sound in some form.', array('class'=>'ignore', 'data-tabcontrol'=>'sound'));
$page->add_spacer($index);
$page->add_form_group($index, 'This art car has flame effects', 'has_fe', 'checkbox', 'This art car has flame effects.', array('class'=>'ignore', 'data-tabcontrol'=>'fe'));
$page->add_spacer($index);

$index = $page->add_wizard_step('Images');
$page->add_form_group($index, 'Image #1', 'image_1', 'file', 'A picture or drawing of your art car.', array('required'=>'true'));
$page->add_spacer($index);
$page->add_form_group($index, 'Image #2', 'image_2', 'file', 'A picture or drawing of your art car.');
$page->add_spacer($index);
$page->add_form_group($index, 'Image #3', 'image_3', 'file', 'A picture or drawing of your art car.');
$page->add_spacer($index);

$index = $page->add_wizard_step('Sound', 'sound');
$page->add_form_group($index, 'Sound System Description', 'sound_desc', 'textarea', 'Describe your sound equipment and how you plan to adhere to the Event Sound Policy');
$page->add_spacer($index);

$index = $page->add_wizard_step('Flame Effects', 'fe');
$page->add_form_group($index, 'Flame Effects Description', 'fire_flameEffects', 'textarea', 'Describe any flame effects such as propane or other flame effects.');
$page->add_spacer($index);

$index = $page->add_wizard_step('Final');
$page->add_raw_html($index, '<div class="alert alert-info" role="alert">Thank you for submitting your art car registration. To finalize your registration (you will not be able to edit it after this) please click "Save and Finish".</div>');
$page->add_spacer($index);

$page->printPage();
