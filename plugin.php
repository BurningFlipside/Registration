<?php
class RegistrationPlugin extends SecurePlugin
{
    function get_secure_menu_entries($page, $user)
    {
        $ret = array('View Registrations'=>$page->secure_root.'register/view.php',
                     'Theme Camp Registration'=>$page->secure_root.'register/tc_reg.php',
                     'Art Project Registration'=>$page->secure_root.'register/art_reg.php',
                     'Art Car Registration'=>$page->secure_root.'register/artCar_reg.php',
                     'Event Registration'=>$page->secure_root.'register/event_reg.php');
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
