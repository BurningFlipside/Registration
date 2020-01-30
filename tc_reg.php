<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterWizardPage.php');
$page = new RegisterWizardPage('Theme Camp', 'Camp');

$index = $page->add_wizard_step('Basic Questions');
$page->add_form_group($index, 'Number of campers', 'num_campers', 'number', '', array('required'=>true));
$page->add_form_group($index, 'This camp has been registered at Burning Flipside in a previous year.', 'camp_reg_prev', 'checkbox', 'This camp has been registered at Burning Flipside in a previous year.');
$page->add_form_group($index, 'This camp has amplified sound, not including personal bluetooth speakers.', 'has_sound', 'checkbox', 'This camp has any form of amplified sound not including something small like a bluetooth speaker.', array('onclick'=>'toggleClassVisible(this, \'sound\')'));
$page->add_form_group($index, 'Sound System Description', 'sound_desc', 'textarea', 'Describe your sound equipment and how you plan to adhere to the Event Sound Policy', array('groupClass' => 'sound d-none'));
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
    array('value'=>'2100', 'text'=>'9 PM'),
    array('value'=>'2200', 'text'=>'10 PM'),
    array('value'=>'2300', 'text'=>'11 PM'),
    array('value'=>'0000', 'text'=>'Midnight')
);
$page->add_form_group($index, 'Sound Hours - From:', 'sound_from', 'select', 'When will you start sound on your project.', array('options'=>$options, 'groupClass' => 'sound d-none'));
$page->add_form_group($index, 'Sound Hours - To:', 'sound_to', 'select', 'When will you stop sound on your project.', array('options'=>$options, 'groupClass' => 'sound d-none'));
$page->add_form_group($index, 'This camp will have burnable art or flame effects', 'has_burnable', 'checkbox', 'This camp will have burnable art or flame effects.');
$page->add_form_group($index, 'This camp will serve food to participants', 'has_food', 'checkbox', 'This camp will have Food (example: grilled cheese giveaway at noon).');
$page->add_form_group($index, 'This camp will have Drinks/Bar/Coffee/Tea', 'has_beverage', 'checkbox', 'This camp will have Drinks/Bar/Coffee/Tea.');
$page->add_form_group($index, 'This camp will have X-Rated Activities', 'has_sex', 'checkbox', 'This camp will have X-Rated Activities (sex/fetish/kink related).');
$page->add_form_group($index, 'This camp will have Spa/Massage', 'has_spa', 'checkbox', 'This camp will have Spa/Massage.');
$page->add_form_group($index, 'This camp is Kid-Friendly', 'has_kids', 'checkbox', 'This camp will be Kid-Friendly.');
$page->add_form_group($index, 'This camp will need heavy equipment', 'has_heavy', 'checkbox', 'This camp will need heavy equipment to build one or more structures.');
$page->add_raw_html($index, '<h3>About Early Arrival</h3>
<p>Early Arrival (arriving Wednesday afternoon) is ONLY to help ensure the safe and smooth arrival of Flipizens and certain infrastructure prior to the opening of the event. City Planning will review your request and determine if it’s in the best interest of the event to approve early arrival. Prior to requesting early arrival, please <a href="'.$page->wwwUrl.'/event/theme-camps/faq">review the FAQ</a>. If you would like to be considered for early arrival for art projects, please register your art on the art registration form.</p>
<p style="font-weight: bold;">Theme camp early arrival does not exist so that people can have a fully functional camp before the event starts Thursday morning.</p>
<p>It requires a great deal of work to help facilitate folk coming in who are setting up their projects. The event volunteers and safety teams work double time to make early arrival possible for those that really need it, so please understand that not everyone who is going to desire early arrival will get a positive response.</p>');
$page->add_form_group($index, 'I would like my camp to be considered for Early Arrival', 'earlyArrival_bool', 'checkbox', 'I feel that my camp meets the criterea for Early Arrival and would like the City Planning team to consider allowing us this gift. I understand that checking this does not guarantee my camp Early Arrival.', array('onclick'=>'toggleVisible(this, \'earlyArrival_desc\')'));
$page->add_form_group($index, 'Please describe the special circumstances which you believe require Early Arrival:', 'earlyArrival_desc', 'textarea', 'Describe why you believe you need Early Arrival.', array('groupClass' => 'd-none'));


$index = $page->add_wizard_step('Camp Contacts');
$page->add_raw_html($index, '<div class="alert alert-info" role="alert">The email provided for the theme camp lead will be automatically added to the theme camp lead’s newsletter. This newsletter is designed to provide theme camp leads with valuable event and community information. The email addresses will be purged just before theme camp registration opens next year.</div>');
$page->add_raw_html($index, '<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                    aria-controls="collapseOne">
                    Camp Lead
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="form-group">
                    <label for="campLead_name">Full Name:</label>
                    <input class="form-control" type="text" name="campLead_name" id="campLead_name" data-toggle="tooltip"
                        data-placement="top" title="" required="" data-original-title="This is the name of the camp lead">
                </div>

                <div class="form-group">
                    <label for="campLead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="campLead_burnerName" id="campLead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="This is the burner name/nickname of the camp lead"
                        aria-describedby="tooltip633155">
                    <div class="tooltip fade top in" role="tooltip" id="tooltip633155" style="top: -50px; left: 341.336px; display: block;">
                        <div class="tooltip-arrow" style="left: 50%;"></div>
                        <div class="tooltip-inner">This is the burner name/nickname of the camp lead</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="campLead_email">Email Address:</label>
                    <input class="form-control" type="text" name="campLead_email" id="campLead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" disabled=""
                        data-original-title="This is the email address of the camp lead">
                </div>

                <div class="form-group">
                    <label for="campLead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="campLead_phone" id="campLead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="This is the phone number of the camp lead">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="campLead_sms" id="campLead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="This phone number can be used to recieve text messages">
                    <label for="campLead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="campLead_just_me" id="campLead_just_me"
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="The camp lead will be contact for all issues about the camp including safety, cleanup, volunteering, and sound.">
                    <label for="campLead_just_me" class="form-check-label non-required">The camp lead is the only contact</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                    aria-controls="collapseTwo">
                    Safety Lead
                </button>
            </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">

                <div class="form-group">
                    <label for="safetyLead_name">Full Name:</label>
                    <input class="form-control" type="text" name="safetyLead_name" id="safetyLead_name"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_name"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the name of the safety lead">
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="safetyLead_burnerName" id="safetyLead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="campLead_burnerName"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the burner name/nickname of the safety lead">
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_email">Email Address:</label>
                    <input class="form-control" type="text" name="safetyLead_email" id="safetyLead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_email"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the email address of the safety lead">
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="safetyLead_phone" id="safetyLead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_phone"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the phone number of the safety lead">
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="safetyLead_sms" id="safetyLead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="campLead_sms"
                        data-copytrigger="#campLead_just_me" data-original-title="This phone number can be used to recieve text messages">
                    <label for="safetyLead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_plan">How will your camp handle Safety/Fire/Injury issues?</label>
                    <textarea class="form-control" rows="6" name="safetyLead_plan" id="safetyLead_plan"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="How will your camp handle Safety/Fire/Injury issues?"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="false" aria-controls="collapseThree">
                    Cleanup Lead
                </button>
            </h5>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body">

                <div class="form-group">
                    <label for="cleanupLead_name">Full Name:</label>
                    <input class="form-control" type="text" name="cleanupLead_name" id="cleanupLead_name"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_name"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the name of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="cleanupLead_burnerName" id="cleanupLead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="campLead_burnerName"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the burner name/nickname of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_email">Email Address:</label>
                    <input class="form-control" type="text" name="cleanupLead_email" id="cleanupLead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_email"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the email address of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="cleanupLead_phone" id="cleanupLead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_phone"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the phone number of the cleanup lead">
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cleanupLead_sms" id="cleanupLead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="campLead_sms"
                        data-copytrigger="#campLead_just_me" data-original-title="This phone number can be used to recieve text messages">
                    <label for="cleanupLead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_plan">How will your camp ensure you leave no trace?</label>
                    <textarea class="form-control" rows="6" name="cleanupLead_plan" id="cleanupLead_plan"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="How will your camp ensure you leave no trace?"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingFour">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false"
                    aria-controls="collapseFive">
                    Volunteer Lead
                </button>
            </h5>
        </div>
        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
            <div class="card-body">

                <div class="form-group">
                    <label for="volunteering_name">Full Name:</label>
                    <input class="form-control" type="text" name="volunteering_name" id="volunteering_name"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_name"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the name of the volunteering">
                </div>
                
                <div class="form-group">
                    <label for="volunteering_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="volunteering_burnerName" id="volunteering_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="campLead_burnerName"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the burner name/nickname of the volunteering">
                </div>
                
                <div class="form-group">
                    <label for="volunteering_email">Email Address:</label>
                    <input class="form-control" type="text" name="volunteering_email" id="volunteering_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_email"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the email address of the volunteering">
                </div>
                
                <div class="form-group"><label for="volunteering_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="volunteering_phone" id="volunteering_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="campLead_phone"
                        data-copytrigger="#campLead_just_me" data-original-title="This is the phone number of the volunteering">
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="volunteering_sms" id="volunteering_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="campLead_sms"
                        data-copytrigger="#campLead_just_me" data-original-title="This phone number can be used to recieve text messages">
                    <label for="volunteering_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>

                <div class="form-group">
                    <label for="volunteering_plan">Volunteer skills for the event:</label>
                    <textarea class="form-control" rows="6" name="volunteering_plan" id="volunteering_plan"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="What skills would your camp like to share with the event?"></textarea>
                </div>

            </div>
        </div>
    </div>
</div>');

$index = $page->add_wizard_step('Placement Information');
$page->add_raw_html($index, '<div class="embed-responsive embed-responsive-4by3">
  <object class="embed-responsive-item" type="application/pdf" data="img/guide_map16a.pdf">
      <p>
          <img src="img/guide_map16a.png" class="img-responsive"/>
          Sorry, your browser is unable to display the full res map in line. Click <a href="img/guide_map16a.pdf">here</a> to download it
      </p>
  </object>
</div>');
$options = array(
    array('value'=>'any', 'text'=>'Any', 'selected'=>TRUE),
    array('value'=>'borderlands', 'text'=>'Borderlands (includes RV Park)'),
    array('value'=>'corral', 'text'=>'Corral'),
    array('value'=>'effigy', 'text'=>'Effigy Loop'),
    array('value'=>'mid', 'text'=>'Mid-City')
);
$page->add_form_group($index, 'Preference 1:', 'placement_pref1', 'select', 'Your first choice for a general type of placement.', array('options'=>$options));
$page->add_form_group($index, 'Preference 2:', 'placement_pref2', 'select', 'Your second choice for a general type of placement.', array('options'=>$options));
$page->add_form_group($index, 'Preference 3:', 'placement_pref3', 'select', 'Your third choice for a general type of placement.', array('options'=>$options));
$page->add_form_group($index, 'Preference Description:', 'placement_desc', 'textarea', 'Describe your ideal camping spot. Please include any camps you would like to be near or to avoid.');
$page->add_form_group($index, 'Special Considerations:', 'placement_special', 'textarea', 'Does your camp have special considerations such as accessibility concerns?.');


$index = $page->add_wizard_step('Camp Infrastructure');
$page->add_form_group($index, 'Number of Standard Size (10x10 or less) Tents:', 'placement_tents', 'number', 'Number of Standard Size (10x10 or less) Tents', array('required'=>true));
$page->add_raw_html($index, '<div class="alert alert-info" role="alert">Please note that the only vehicles permitted to be left in theme camp spaces are artified cars/trucks used for car camping and registered RVs. To ensure your vehicle meets our guidelines, please visit <a href="'.$page->wwwUrl.'/sg" class="alert-link">'.$page->wwwUrl.'/sg</a> for more information. Vehicles that do not meet our criteria will need to be moved to Parking.</div>');
$page->add_raw_html($index, '<div class="alert alert-danger" role="alert"><span class="fa fa-fire" aria-hidden="true"></span> Pyro art must be registered separately on the art registration form <a href="art_reg.php" class="alert-link">here</a>. Please do note on that form that this piece is part of a theme camp.</div>');
$page->add_raw_html($index, '<div class="alert alert-info" role="alert"><span class="fa fa-map" aria-hidden="true"></span> Any art pieces to be included on the map and art cars attending Flipside must also be registered on the art registration form or DMV form <a href="add.php" class="alert-link">here</a>.</div>');
$page->add_raw_html($index, '
    <table id="structs_table" class="table table-responsive">
        <thead>
            <tr>
                <th class="col-xs-1"></th>
                <th class="col-xs-2">Type</th>
                <th class="col-xs-1">Width (in feet)</th>
                <th class="col-xs-1">Length (in feet)</th>
                <th class="col-xs-1">Height (in feet)</th>
                <th class="col-xs-4">Description</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <div>
                        <button type="button" class="btn btn-primary" id="add_new_struct" onclick="add_new_struct_to_table()">Add New Structure</button>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>');

if(isset($_GET['is_admin']))
{
    $user = $page->user;
    if($user->isInGroupNamed('RegistrationAdmins') || $user->isInGroupNamed('CampAdmins'))
    {
        $index = $page->add_wizard_step('Admin Data');
        $page->add_form_group($index, 'Placement ID:', 'location', 'text', 'The ID on the map.');
        
        $page->add_form_group($index, 'City Planning Notes:', 'cityplanning_notes', 'textarea');
        
    }
}

$page->printPage();

function camelize($value)
{
    return lcfirst(strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => '')));
}
