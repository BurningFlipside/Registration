<?php
require_once('class.SecurePage.php');
class RegisterPage extends SecurePage
{
    public $register_root;

    function __construct($title)
    {
        parent::__construct($title, true);
        $root = $_SERVER['DOCUMENT_ROOT'];
        $script_dir = dirname(__FILE__);
        $this->register_root = substr($script_dir, strlen($root));
        $this->add_links();
    }

    function add_links()
    {
        $dir = $this->register_root;
        if(!FlipSession::is_logged_in())
        {
            $this->add_link('Login', 'http://profiles.burningflipside.com/login.php?return='.$this->current_url());
        }
        else
        {
            $user = FlipSession::get_user(true);
            if($user->isInGroupNamed('RegistrationAdmins') ||
               $user->isInGroupNamed('ArtAdmins')          ||
               $user->isInGroupNamed('CampAdmins')         ||
               $user->isInGroupNamed('DMVAdmins')          ||
               $user->isInGroupNamed('EventAdmins'))
            { 
                $this->add_link('Admin', $dir.'/_admin/');
            }
            $secure_menu = array(
                'Tickets'=>'/tickets/index.php',
                'View Registrations'=>$dir.'/view.php',
                'Theme Camp Registration'=>$dir.'/tc_reg.php',
                'Art Project Registration'=>$dir.'/art_reg.php',
                'Art Car Registration'=>$dir.'/artCar_reg.php',
                'Event Registration'=>$dir.'/event_reg.php'
            );
            $this->add_link('Secure', 'https://secure.burningflipside.com/', $secure_menu);
            $this->add_link('Logout', 'http://profiles.burningflipside.com/logout.php');
        }
        $about_menu = array(
            'Burning Flipside'=>'http://www.burningflipside.com/about/event',
            'AAR, LLC'=>'http://www.burningflipside.com/LLC',
            'Privacy Policy'=>'http://www.burningflipside.com/about/privacy'
        );
        $this->add_link('About', 'http://www.burningflipside.com/about', $about_menu);
    }
}
?>
