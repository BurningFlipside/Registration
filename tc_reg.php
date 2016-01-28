<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('class.RegisterWizardPage.php');
$page = new RegisterWizardPage('Theme Camp', 'Camp');

$page->add_js_from_src('js/tc_reg.js');

$index = $page->add_wizard_step('Basic Questions');
$page->add_form_group($index, 'This camp has been previously registered at Flipside', 'camp_reg_prev', 'checkbox', 'This camp has been registered at Burning Flipside in a previous year.', array('class'=>'ignore', 'data-tabcontrol'=>'prev_camp'));
$page->add_spacer($index);
$page->add_form_group($index, 'This camp has amplified sound', 'has_sound', 'checkbox', 'This camp has any form of amplified sound.', array('class'=>'ignore', 'data-tabcontrol'=>'sound_step', 'data-groupcontrol'=>'soundLead'));
$page->add_spacer($index);

$index = $page->add_wizard_step('Previous Camp Information','prev_camp');
$page->add_form_group($index, 'Previous Camp Name(s):', 'prevInfo_name', 'text', 'A list of names your camp has used previously.');
$page->add_spacer($index);
$page->add_form_group($index, 'Previous Number of Campers:', 'prevInfo_campers', 'text', 'The number of campers your camp had in the most recent year it was registered at Flipside.');
$page->add_spacer($index);

$index = $page->add_wizard_step('Camp Contacts');
$page->add_raw_html($index, '<div class="alert alert-info" role="alert">The email provided for the theme camp lead will be automatically added to the theme camp lead’s newsletter. This newsletter is designed to provide theme camp leads with valuable event and community information. The email addresses will be purged just before theme camp registration opens next year.</div>');
$page->add_form_group($index, 'The camp lead is the only camp contact', 'just_me', 'checkbox', 'The camp lead will be contact for all issues about the camp including safety, cleanup, volunteering, and sound.', array('class'=>'ignore'));
$page->add_spacer($index);
$accordion_ref = $page->add_accordion($index);
$panels = array('Camp Lead', 'Safety Lead', 'Cleanup Lead', 'Volunteering', 'Sound Lead');
$all_panels = array(
                  array('label'=>'Name:', 'name'=>'name', 'type'=>'text', 'tooltip'=>'This is the name of the %s', 'required'=>TRUE),
                  array('label'=>'Burner Name:', 'name'=>'burnerName', 'type'=>'text', 'tooltip'=>'This is the burner name/nickname of the %s'),
                  array('label'=>'Email:', 'name'=>'email', 'type'=>'text', 'tooltip'=>'This is the email address of the %s', 'required'=>TRUE),
                  array('label'=>'Phone:', 'name'=>'phone', 'type'=>'text', 'tooltip'=>'This is the phone number of the %s'),
                  array('label'=>'This number can receive SMS messages:', 'name'=>'sms', 'type'=>'checkbox', 'tooltip'=>'This phone number can be used to recieve text messages'),
              );
$other = array(
    'Safety Lead' => array('label'=>'How will your camp handle Safety/Fire/Injury issues?', 'name'=>'plan', 'type'=>'textarea', 'tooltip'=>'How will your camp handle Safety/Fire/Injury issues?', 'required'=>TRUE),
    'Cleanup Lead' => array('label'=>'How will your camp ensure you leave no trace?', 'name'=>'plan', 'type'=>'textarea', 'tooltip'=>'How will your camp ensure you leave no trace?', 'required'=>TRUE),
    'Volunteering'=> array('label'=>'Volunteer skills for the event:', 'name'=>'plan', 'type'=>'textarea', 'tooltip'=>'What skills would your camp like to share with the event?', 'required'=>TRUE),
);
$panel_count = count($panels);
$content_count = count($all_panels);
for($i = 0; $i < $panel_count; $i++)
{
    $panel_ref = $page->add_accordion_panel($accordion_ref, $panels[$i]);
    $camel = camelize($panels[$i]);
    $lower = strtolower($panels[$i]);
    for($j = 0; $j < $content_count; $j++)
    {
        $tooltip = sprintf($all_panels[$j]['tooltip'], $lower);
        if(isset($all_panels[$j]['required']))
        {
            $extra = array('required'=>$all_panels[$j]['required']);
        }
        else
        {
            $extra = FALSE;
        }
        if($i > 0)
        {
            if($extra === FALSE)
            {
                $extra = array();
            }
            $extra['data-copyfrom'] = camelize($panels[0]).'_'.$all_panels[$j]['name'];
            $extra['data-copytrigger'] = '#just_me';
        }
        else
        {
            if($all_panels[$j]['name'] === 'email')
            {
                if($extra === FALSE)
                {
                    $extra = array();
                }
                $extra['disabled'] = true;
            }
        }
        $page->add_form_group($panel_ref, $all_panels[$j]['label'], $camel.'_'.$all_panels[$j]['name'], $all_panels[$j]['type'], $tooltip, $extra);
        $page->add_spacer($panel_ref);
    }
    if(isset($other[$panels[$i]]))
    {
        $tooltip = sprintf($other[$panels[$i]]['tooltip'], $lower);
        if(isset($other[$panels[$i]]['required']))
        {
            $extra = array('required'=>$other[$panels[$i]]['required']);
        }
        else
        {
            $extra = FALSE;
        }
        $page->add_form_group($panel_ref, $other[$panels[$i]]['label'], $camel.'_'.$other[$panels[$i]]['name'], $other[$panels[$i]]['type'], $tooltip, $extra);
        $page->add_spacer($panel_ref);
    }
}

$index = $page->add_wizard_step('Sound Information','sound_step');
$page->add_form_group($index, 'Sound System Description', 'sound_desc', 'textarea', 'Describe your sound equipment and how you plan to adhere to the Event Sound Policy');
$page->add_spacer($index);
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
$page->add_form_group($index, 'Sound Hours - From:', 'sound_from', 'select', 'When will you start sound on your project.', array('options'=>$options));
$page->add_spacer($index);
$page->add_form_group($index, 'Sound Hours - To:', 'sound_to', 'select', 'When will you stop sound on your project.', array('options'=>$options));
$page->add_spacer($index);

$index = $page->add_wizard_step('Placement Information');
$page->add_form_group($index, 'Number of Campers:', 'placement_campers', 'text', 'The number of campers your camp plans to have this year.');
$page->add_spacer($index);
$page->add_raw_html($index, '<div class="embed-responsive embed-responsive-4by3">
  <object class="embed-responsive-item" type="application/pdf" data="img/guide_map15a.pdf">
      <p>
          <img src="img/guide_map15a.png" class="img-responsive"/>
          Sorry, your browser is unable to display the full res map in line. Click <a href="img/guide_map15a.pdf">here</a> to download it
      </p>
  </object>
</div>');
$options = array(
    array('value'=>'any', 'text'=>'Any', 'selected'=>TRUE),
    array('value'=>'effigyLoopLoud', 'text'=>'Effigy Loop - Loud'),
    array('value'=>'centralLoud', 'text'=>'Central - Loud'),
    array('value'=>'centralLessLoud', 'text'=>'Central - Less Loud'),
    array('value'=>'badlandsLoud', 'text'=>'Badlands - Loud'),
    array('value'=>'badlandsLessLoud', 'text'=>'Badlands - Less Loud'),
    array('value'=>'corralLessLoud', 'text'=>'Corral - Less Loud'),
    array('value'=>'rvPark', 'text'=>'RV Park')
);
$page->add_form_group($index, 'Preference 1:', 'placement_pref1', 'select', 'Your first choice for a general type of placement.', array('options'=>$options));
$page->add_spacer($index);
$page->add_form_group($index, 'Preference 2:', 'placement_pref2', 'select', 'Your second choice for a general type of placement.', array('options'=>$options));
$page->add_spacer($index);
$page->add_form_group($index, 'Preference 3:', 'placement_pref3', 'select', 'Your third choice for a general type of placement.', array('options'=>$options));
$page->add_spacer($index);
$page->add_form_group($index, 'Preference Description:', 'placement_desc', 'textarea', 'Describe your ideal camping spot. Please include any camps you would like to be near or to avoid.');
$page->add_spacer($index);
$page->add_form_group($index, 'Special Considerations:', 'placement_special', 'textarea', 'Does your camp have special considerations such as accessibility concerns?.');
$page->add_spacer($index);
$page->add_raw_html($index, '<h3>About Early Arrival</h3>
<p>Early Arrival (arriving Wednesday afternoon) for theme camps was created to help folk with larger, more involved projects and/or infrastructure set up safely, with a bit more time and space before the gates of the event officially open. Many things are taken into consideration when evaluating the benefit of early arrival and it is largely a combination of circumstances that help us to determine where the gift of early arrival is most beneficial. Some structures require a wide perimeter to set up safely, some camps and villages bring in complicated power grids that need to be organized and laid out before the rush, some structures require the assistance of heavy machinery, we try to avoid having large trucks block the road- there are any number of reasons that we will consider it safer and beneficial for the community to invite some people in to get this foundation set. There are no set rules or formulas- we do our best using the information provided us through theme camp registration to assess the benefit of early arrival based on theme camp infrastructure.</p>
<p style="font-weight: bold;">Theme camp early arrival does not exist so that people can have a fully functional camp before the event starts Thursday morning.</p>
<p>It requires a great deal of work to help facilitate folk coming in who are setting up their projects. The event volunteers and safety teams work double time to make early arrival possible for those that really need it, so please understand that not everyone who is going to desire early arrival will get a positive response.</p>');
$page->add_spacer($index);
$page->add_form_group($index, 'I would like my camp to be considered for Early Arrival', 'earlyArrival_bool', 'checkbox', 'i feel that my camp meets the criterea for Early Arrival and would like the City Planning team to consider allowing us this gift. I understand that checking this does not guarantee my camp Early Arrival.', array('data-questcontrol'=>'earlyArrival_desc'));
$page->add_spacer($index);
$page->add_form_group($index, 'Please describe the special circumstances which you believe require Early Arrival:', 'earlyArrival_desc', 'textarea', 'Describe why you believe you need Early Arrival.');
$page->add_spacer($index);

$index = $page->add_wizard_step('Camp Infrastructure');
$page->add_form_group($index, 'Number of Standard (4 Person or fewer) Tents:', 'placement_tents', 'text', 'Number of Tents less than 10 feet x 10 feet in size');
$page->add_spacer($index);
$page->add_raw_html($index, '<div class="alert alert-info" role="alert">Please note that the only vehicles permitted to be left in theme camp spaces are artified cars/trucks used for car camping and registered RVs. To ensure your vehicle meets our guidelines, please visit <a href="http://www.burningflipside.com/sg" class="alert-link">http://www.burningflipside.com/sg</a> for more information. Vehicles that do not meet our criteria will need to be moved to Parking.</div>');
$page->add_raw_html($index, '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span> Pyro art must be registered separately on the art registration form at <a href="https://secure.burningflipside.com/register/art_reg.php" class="alert-link">https://secure.burningflipside.com/register/art_reg.php</a>. Please do note on that form that this piece is part of a theme camp.</div>');
$page->add_raw_html($index, '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span> Any art pieces to be included on the map and art cars attending Flipside must also be registered on the art registration form or DMV form at <a href="https://secure.burningflipside.com/register/add.php" class="alert-link">https://secure.burningflipside.com/register/add.php</a>.</div>');
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
        $page->add_spacer($index);
        $page->add_form_group($index, 'City Planning Notes:', 'cityplanning_notes', 'textarea');
        $page->add_spacer($index);
    }
}

$page->print_page();

function camelize($value)
{
    return lcfirst(strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => '')));
}
?>
