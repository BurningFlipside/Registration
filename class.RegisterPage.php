<?php
require_once('../class.SecurePage.php');
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

    public function printPage($header = true)
    {
        if($this->user === false || $this->user === null)
        {
            $this->body = '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">You must <a href="'.$this->loginUrl.'?return='.$this->currentUrl().'">log in <span class="glyphicon glyphicon-log-in"></span></a> to access the '.$this->title.' system!</h1>
            </div>
        </div>';
        }
        parent::printPage();
    }
}
