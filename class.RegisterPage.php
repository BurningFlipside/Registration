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
        $user = FlipSession::get_user(true);
        if($user->isInGroupNamed('RegistrationAdmins') ||
           $user->isInGroupNamed('ArtAdmins')          ||
           $user->isInGroupNamed('CampAdmins')         ||
           $user->isInGroupNamed('DMVAdmins')          ||
           $user->isInGroupNamed('EventAdmins'))
        {
           $this->add_link('Admin', $this->register_root.'/_admin/');
        }
    }
}
?>
