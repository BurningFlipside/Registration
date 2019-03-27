<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterWizardPage.php');
$page = new RegisterWizardPage('Art Project');

$page->addWellKnownJS(JS_BOOTBOX, false);
$page->addJS('js/art_reg.js');


$index = $page->add_wizard_step('Basic Questions');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="need_logistics" id="need_logistics"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="This project has logistical needs such as needing help transporting to the site, heavy equipment needs, outside volunteers etc.">
    <label for="need_logistics" class="form-check-label non-required">This project needs logistical help</label>
</div>');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="has_sound" id="has_sound"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="This project will utilize amplified sound in some form.">
    <label for="has_sound" class="form-check-label non-required">This project has sound</label>
</div>');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="has_fe" id="has_fe"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="This project has flame effects (propane or other combustable non-consuming effects).">
    <label for="has_fe" class="form-check-label non-required">This project has flame effects</label>
</div>');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="will_burn" id="will_burn"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="This project has flame effects or I would like to burn this piece.">
    <label for="will_burn" class="form-check-label non-required">I would like to burn this project</label>
</div>');


$index = $page->add_wizard_step('Images');
$page->add_form_group($index, 'Image #1', 'image_1', 'file', 'A picture or drawing of your art.');
$page->add_form_group($index, 'Image #2', 'image_2', 'file', 'A picture or drawing of your art.');
$page->add_form_group($index, 'Image #3', 'image_3', 'file', 'A picture or drawing of your art.');


$index = $page->add_wizard_step('Art Team Contacts');
$page->add_raw_html($index, '<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                    aria-controls="collapseOne">
                    Art Lead
                </button>
            </h5>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="form-group">
                    <label for="artlead_name">Full Name:</label>
                    <input class="form-control" type="text" name="artlead_name" id="artlead_name" data-toggle="tooltip"
                        data-placement="top" title="" required="" data-original-title="This is the name of the camp lead">
                </div>

                <div class="form-group">
                    <label for="artlead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="artlead_burnerName" id="artlead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="This is the burner name/nickname of the camp lead"
                        aria-describedby="tooltip633155">
                    <div class="tooltip fade top in" role="tooltip" id="tooltip633155" style="top: -50px; left: 341.336px; display: block;">
                        <div class="tooltip-arrow" style="left: 50%;"></div>
                        <div class="tooltip-inner">This is the burner name/nickname of the camp lead</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="artlead_email">Email Address:</label>
                    <input class="form-control" type="text" name="artlead_email" id="artlead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" disabled=""
                        data-original-title="This is the email address of the camp lead">
                </div>

                <div class="form-group">
                    <label for="artlead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="artlead_phone" id="artlead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="This is the phone number of the camp lead">
                </div>

                <div class="form-group">
                    <label for="artLead_camp">Theme Camp Affiliation:</label>
                    <input class="form-control" type="text" name="artLead_camp" id="artLead_camp"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="The Theme Camp where art lead will camp.">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="artlead_sms" id="artlead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="This phone number can be used to recieve text messages">
                    <label for="artlead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="artlead_just_me" id="artlead_just_me"
                        data-toggle="tooltip" data-placement="top" title="" data-original-title="The art lead will be contact for all issues about the art including safety, cleanup, fire, and sound.">
                    <label for="artlead_just_me" class="form-check-label non-required">The art lead is the only contact</label>
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
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_name"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the name of the safety lead">
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="safetyLead_burnerName" id="safetyLead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_burnerName"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the burner name/nickname of the safety lead">
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_email">Email Address:</label>
                    <input class="form-control" type="text" name="safetyLead_email" id="safetyLead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_email"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the email address of the safety lead">
                </div>
                
                <div class="form-group">
                    <label for="safetyLead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="safetyLead_phone" id="safetyLead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_phone"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the phone number of the safety lead">
                </div>

                <div class="form-group">
                    <label for="safetylead_camp">Theme Camp Affiliation:</label>
                    <input class="form-control" type="text" name="safetylead_camp" id="safetylead_camp"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="The Theme Camp where safety lead will camp.">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="safetyLead_sms" id="safetyLead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_sms"
                        data-copytrigger="#artlead_just_me" data-original-title="This phone number can be used to recieve text messages">
                    <label for="safetyLead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
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
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_name"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the name of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="cleanupLead_burnerName" id="cleanupLead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_burnerName"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the burner name/nickname of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_email">Email Address:</label>
                    <input class="form-control" type="text" name="cleanupLead_email" id="cleanupLead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_email"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the email address of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="cleanupLead_phone" id="cleanupLead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_phone"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the phone number of the cleanup lead">
                </div>
                
                <div class="form-group">
                    <label for="cleanupLead_camp">Theme Camp Affiliation:</label>
                    <input class="form-control" type="text" name="cleanupLead_camp" id="cleanupLead_camp"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="The Theme Camp where cleanup lead will camp.">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cleanupLead_sms" id="cleanupLead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_sms"
                        data-copytrigger="#artlead_just_me" data-original-title="This phone number can be used to recieve text messages">
                    <label for="cleanupLead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="headingFive">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false"
                    aria-controls="collapseFive">
                    Fire Lead
                </button>
            </h5>
        </div>
        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
            <div class="card-body">

                <div class="form-group">
                    <label for="firelead_name">Full Name:</label>
                    <input class="form-control" type="text" name="firelead_name" id="firelead_name"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_name"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the name of the volunteering">
                </div>
                
                <div class="form-group">
                    <label for="firelead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="firelead_burnerName" id="firelead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_burnerName"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the burner name/nickname of the volunteering">
                </div>
                
                <div class="form-group">
                    <label for="firelead_email">Email Address:</label>
                    <input class="form-control" type="text" name="firelead_email" id="firelead_email"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_email"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the email address of the volunteering">
                </div>
                
                <div class="form-group"><label for="firelead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="firelead_phone" id="firelead_phone"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-copyfrom="artlead_phone"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the phone number of the volunteering">
                </div>
                
                <div class="form-group">
                    <label for="firelead_camp">Theme Camp Affiliation:</label>
                    <input class="form-control" type="text" name="firelead_camp" id="firelead_camp"
                        data-toggle="tooltip" data-placement="top" title="" required="" data-original-title="The Theme Camp where fire lead will camp.">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="firelead_sms" id="firelead_sms"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_sms"
                        data-copytrigger="#artlead_just_me" data-original-title="This phone number can be used to recieve text messages">
                    <label for="firelead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="headingSix">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false"
                    aria-controls="collapseSix">
                    Sound Lead
                </button>
            </h5>
        </div>
        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
            <div class="card-body">

                <div class="form-group">
                    <label for="soundlead_name">Full Name:</label>
                    <input class="form-control" type="text" name="soundlead_name" id="soundlead_name" data-toggle="tooltip"
                        data-placement="top" title="" required="" data-copyfrom="artlead_name" data-copytrigger="#artlead_just_me"
                        data-original-title="This is the name of the volunteering">
                </div>

                <div class="form-group">
                    <label for="soundlead_burnerName" class="non-required">Burner Name:</label>
                    <input class="form-control" type="text" name="soundlead_burnerName" id="soundlead_burnerName"
                        data-toggle="tooltip" data-placement="top" title="" data-copyfrom="artlead_burnerName"
                        data-copytrigger="#artlead_just_me" data-original-title="This is the burner name/nickname of the volunteering">
                </div>

                <div class="form-group">
                    <label for="soundlead_email">Email Address:</label>
                    <input class="form-control" type="text" name="soundlead_email" id="soundlead_email" data-toggle="tooltip"
                        data-placement="top" title="" required="" data-copyfrom="artlead_email" data-copytrigger="#artlead_just_me"
                        data-original-title="This is the email address of the volunteering">
                </div>

                <div class="form-group"><label for="soundlead_phone">Phone Number:</label>
                    <input class="form-control" type="text" name="soundlead_phone" id="soundlead_phone" data-toggle="tooltip"
                        data-placement="top" title="" required="" data-copyfrom="artlead_phone" data-copytrigger="#artlead_just_me"
                        data-original-title="This is the phone number of the volunteering">
                </div>

                <div class="form-group">
                    <label for="soundlead_camp">Theme Camp Affiliation:</label>
                    <input class="form-control" type="text" name="soundlead_camp" id="soundlead_camp" data-toggle="tooltip"
                        data-placement="top" title="" required="" data-original-title="The Theme Camp where fire lead will camp.">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="soundlead_sms" id="soundlead_sms" data-toggle="tooltip"
                        data-placement="top" title="" data-copyfrom="artlead_sms" data-copytrigger="#artlead_just_me"
                        data-original-title="This phone number can be used to recieve text messages">
                    <label for="soundlead_sms" class="form-check-label non-required">This number can receive SMS messages:</label>
                </div>

            </div>
        </div>
    </div>
</div>');


$index = $page->add_wizard_step('Placement Information');
$page->add_raw_html($index, '<div class="alert alert-info" role="alert"><span class="fa fa-bed" aria-hidden="true"></span> If this piece is to be placed with your theme camp, be sure it\'s footprint is included on the theme camp registration form <a href="add.php" class="alert-link" target="_blank">here</a>.</div>');
$page->add_raw_html($index, '<div class="embed-responsive embed-responsive-4by3">
  <object class="embed-responsive-item" type="application/pdf" data="img/guide_map16a.pdf">
      <p>
          <img src="img/guide_map16a.png" class="img-responsive"/>
          Sorry, your browser is unable to display the full res map in line. Click <a href="img/guide_map16a.pdf">here</a> to download it
      </p>
  </object>
</div>');
$page->add_form_group($index, 'Show on map:', 'placement_on_map', 'checkbox', 'Check this box if you want your art installation to be shown on city planning map.');
$page->add_form_group($index, 'In Theme Camp:', 'placement_in_camp', 'text', 'If your art will be placed within the borders of a theme camp enter the camp name here.');
$page->add_form_group($index, 'Dimensions (in feet):', 'placement_size', 'text', 'Approximate project dimensions, in feet* [width / length / height]');
$options = array(
    array('value'=>'any', 'text'=>'Any', 'selected'=>TRUE),
    array('value'=>'corral', 'text'=>'Corral'),
    array('value'=>'crossroads', 'text'=>'Cross roads'),
    array('value'=>'effigy', 'text'=>'Effigy Field'),
    array('value'=>'island', 'text'=>'Island'),
    array('value'=>'onRoad', 'text'=>'Along a Roadway'),
    array('value'=>'riverwalk', 'text'=>'Riverwalk'),
    array('value'=>'ownCamp', 'text'=>'Own Themecamp'),
    array('value'=>'otherCamp', 'text'=>'Other Themecamp')
);
$page->add_form_group($index, 'Preference 1:', 'placement_pref1', 'select', 'Your first choice for a general type of placement.', array('options'=>$options));
$page->add_form_group($index, 'Preference 2:', 'placement_pref2', 'select', 'Your second choice for a general type of placement.', array('options'=>$options));
$page->add_form_group($index, 'Preference 3:', 'placement_pref3', 'select', 'Your third choice for a general type of placement.', array('options'=>$options));
$page->add_form_group($index, 'Preference Description:', 'placement_desc', 'textarea', 'Describe your ideal installation spot.');


$index = $page->add_wizard_step('Logistics Information', 'logistics');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="logistics_needsTranspo" id="logistics_needsTranspo"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="I need help transporting this project to Burning Flipside.">
    <label for="logistics_needsTranspo" class="form-check-label non-required">I need help transporting this project to Flipside</label>
</div>');
$page->add_form_group($index, 'What are the packed dimensions and the weight of the project', 'logistics_transpoSize', 'textarea');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="logistics_needsHE" id="logistics_needsHE"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="I will need use of the Variable Reach forklift, Trencher, Cherry Picker, or other Heavy Equipment on site if available.">
    <label for="logistics_needsHE" class="form-check-label non-required">I will need help from the heavy equipment on site</label>
</div>');
$page->add_form_group($index, 'Please describe what Heavy Equipment you will need, what it needs to do, and how long you think you will need it for', 'logistics_descHE', 'textarea');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="logistics_needsVols" id="logistics_needsVols"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="I will need help from Flipside volunteers such as Shaven Apes to help assemble, Fire Team, or other volunteers.">
    <label for="logistics_needsVols" class="form-check-label non-required">I will need help from volunteers other than the crew I can bring</label>
</div>');
$page->add_form_group($index, 'Please describe what volunteers you will need, what you need them to do, and for how long you will need them', 'logistics_descVols', 'textarea');


$index = $page->add_wizard_step('Sound', 'sound');
$page->add_form_group($index, 'Sound System Description', 'sound_desc', 'textarea', 'Describe your sound equipment and how you plan to adhere to the Event Sound Policy');
$options = array(
    array('value'=>'', 'text'=>'', 'selected'=>TRUE),
    array('value'=>'all', 'text'=>'All Day'),
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
$page->add_form_group($index, 'Sound Hours - From:', 'sound_from', 'select', 'When will you start sound on your project.', array('options'=>$options));
$page->add_form_group($index, 'Sound Hours - To:', 'sound_to', 'select', 'When will you stop sound on your project.', array('options'=>$options));


$index = $page->add_wizard_step('Fire', 'fire');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="fire_hasFlameEffects" id="fire_hasFlameEffects"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="">
    <label for="fire_hasFlameEffects" class="form-check-label non-required">This project has fire effects</label>
</div>');
$page->add_form_group($index, 'Flame Effects Description', 'fire_flameEffects', 'textarea', 'Describe any flame effects such as propane or other flame effects that do not consume your artwork.');
$options = array(
    array('value'=>'', 'text'=>'', 'selected'=>TRUE),
    array('value'=>'thrusday', 'text'=>'Thursday'),
    array('value'=>'friday', 'text'=>'Friday'),
    array('value'=>'saturday', 'text'=>'Saturday'),
    array('value'=>'sunday', 'text'=>'Sunday')
);
$page->add_form_group($index, 'When do you want to burn?', 'fire_burnDay', 'select', 'When do you plan to burn your project.', array('options'=>$options, 'required'=>TRUE));
$page->add_form_group($index, 'Burn Plan', 'fire_burnPlan', 'textarea', 'Describe how you anticipate burning your art project. Describe what fuel you would use, any pyrotechic effects, when you would enact perimeter, and any other special considerations the fire team would need to know about.', array('required'=>TRUE));
$page->add_form_group($index, 'Cleanup Plan', 'fire_cleanupPlan', 'textarea', 'Describe how you anticipate cleaning your art project after burning.', array('required'=>TRUE));


$index = $page->add_wizard_step('Installation Information');
$page->add_form_group($index, 'Art Removal or UnBurn Plan', 'information_unburn', 'textarea', 'Describe how you intend to remove your project after the event. Please note that "We will burn it." is not an acceptible plan as we may have a burn ban or other reasons why your piece cannot burn.', array('required'=>TRUE));
$page->add_form_group($index, 'Setup/teardown time and requirements', 'information_setup', 'textarea', 'Describe how much time you will need to setup/teardown this project and any special requirements you may have.', array('required'=>TRUE));
$options = array(
    array('value'=>'', 'text'=>'', 'selected'=>TRUE),
    array('value'=>'thrusday', 'text'=>'Thursday'),
    array('value'=>'friday', 'text'=>'Friday'),
    array('value'=>'saturday', 'text'=>'Saturday'),
    array('value'=>'sunday', 'text'=>'Sunday')
);
$page->add_form_group($index, 'Arrival at Flipside:', 'information_arrival', 'select', 'When do you plan to arrive at Flipside.', array('options'=>$options, 'required'=>TRUE));
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="information_power" id="information_power"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="This project will use a generator.">
    <label for="information_power" class="form-check-label non-required">This project requires a generator</label>
</div>');
$page->add_form_group($index, 'Power Requirements/Generator Information', 'information_powerStats', 'textarea', 'Please describe your power needs. And if you are bringing a generator and are interested in power sharing describe how much available power you will have.');
$page->add_raw_html($index, '<div class="form-check">
    <input class="form-check-input" type="checkbox" name="information_laser" id="information_laser"
        data-toggle="tooltip" data-placement="top" title="" 
        data-original-title="This project will use lasers.">
    <label for="information_laser" class="form-check-label non-required">This project includes lasers</label>
</div>');
$page->add_form_group($index, 'Laser installation details', 'information_laserStats', 'textarea', 'Tell us about your lasers. What kind? How high will they be mounted?');
$page->add_form_group($index, 'Other Information', 'information_other', 'textarea', 'Please tell us ANYTHING that we may have missed');


if(isset($_GET['is_admin']))
{
    $user = $page->user;
    if($user->isInGroupNamed('RegistrationAdmins') || $user->isInGroupNamed('ArtAdmins'))
    {
        $index = $page->add_wizard_step('Admin Data');
        $page->add_form_group($index, 'Placement ID:', 'location', 'text', 'The ID on the map.');
        $page->add_form_group($index, 'City Planning Notes:', 'cityplanning_notes', 'textarea');
        $page->add_form_group($index, 'Art Notes:', 'art_notes', 'textarea');        
    }
}

$page->printPage();

function camelize($value)
{
    return lcfirst(strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => '')));
}
