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
        if($this->user !== null && (
           $this->user->isInGroupNamed('RegistrationAdmins') ||
           $this->user->isInGroupNamed('ArtAdmins')          ||
           $this->user->isInGroupNamed('CampAdmins')         ||
           $this->user->isInGroupNamed('DMVAdmins')          ||
           $this->user->isInGroupNamed('EventAdmins')))
        {
           $this->addLink('Admin', $this->register_root.'/_admin/');
        }
    }
}
