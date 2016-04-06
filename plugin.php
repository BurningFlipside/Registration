<?php
class RegistrationPlugin extends SecurePlugin
{
    function get_secure_menu_entries($page, $user)
    {
        $ret = array('View Registrations'=>$page->secure_root.'register/view.php');

        $data_set = false;
        
        try{
        $data_set = DataSetFactory::get_data_set('registration');
        } catch(Exception $e) {
            return;
        }
        $vars_data_table = $data_set['vars'];

        $vars = $vars_data_table->read(new \Data\Filter('name eq tcRegDates'));
        $tcDates = $vars[0]['value'];

        $vars = $vars_data_table->read(new \Data\Filter('name eq artRegDates'));
        $artDates = $vars[0]['value'];

        $vars = $vars_data_table->read(new \Data\Filter('name eq dmvRegDates'));
        $dmvDates = $vars[0]['value'];

        $vars = $vars_data_table->read(new \Data\Filter('name eq eventRegDates'));
        $evtDates = $vars[0]['value'];

        $now = getdate();
        $tcStart  = date_parse($tcDates['start']);
        $tcEnd    = date_parse($tcDates['end']);
        $artStart = date_parse($artDates['start']);
        $artEnd   = date_parse($artDates['end']);
        $dmvStart = date_parse($dmvDates['start']);
        $dmvEnd   = date_parse($dmvDates['end']);
        $evtStart = date_parse($evtDates['start']);
        $evtEnd   = date_parse($evtDates['end']);

        if($now > $tcStart && $now < $tcEnd)
        {
            $ret['Theme Camp Registration']=$page->secure_root.'register/tc_reg.php';
        }

        if($now > $artStart && $now < $artEnd)
        {
            $ret['Art Project Registration']=$page->secure_root.'register/art_reg.php';
        }

        if($now > $dmvStart && $now < $dmvEnd)
        {
            $ret['Art Car Registration']=$page->secure_root.'register/artCar_reg.php';
        }

        if($now > $evtStart && $now < $evtEnd)
        {
            $ret['Event Registration']=$page->secure_root.'register/event_reg.php';
        }

        if($user !== null && 
           ($user->isInGroupNamed('RegistrationAdmins') || $user->isInGroupNamed('ArtAdmins') || $user->isInGroupNamed('CampAdmins') || $user->isInGroupNamed('DMVAdmins') || $user->isInGroupNamed('EventAdmins')))
        {
            $ret['Registration Admin'] = $page->secure_root.'register/_admin/index.php';
        }
        return $ret;
    }

    function get_plugin_entry_point()
    {
        return array('name'=>'Theme Camp, Art Project, Art Car, and Event Registration', 'link'=>'register/index.php');
    }
}
?>
