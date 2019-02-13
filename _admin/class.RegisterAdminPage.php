<?php
require_once('class.FlipSession.php');
require_once('../../class.SecurePage.php');
class RegisterAdminPage extends \Http\FlipAdminPage
{
    use SecureWebPage;

    public  $is_tc_admin;
    public  $is_art_admin;
    public  $is_dmv_admin;
    public  $is_event_admin;

    function __construct($title)
    {
        $this->is_admin       = false;
        $this->is_tc_admin    = false;
        $this->is_art_admin   = false;
        $this->is_dmv_admin   = false;
        $this->is_event_admin = false;
        parent::__construct($title, 'RegistrationAdmins');
        $this->addTemplateDir('../../templates', 'Secure');
        $this->addTemplateDir('../templates', 'Register');
        $this->secure_root = $this->getSecureRoot();
        if($this->user !== false && $this->user !== null)
        {
            if($this->user->isInGroupNamed('RegistrationAdmins'))
            {
                $this->is_admin       = true;
                $this->is_tc_admin    = true;
                $this->is_art_admin   = true;
                $this->is_dmv_admin   = true;
                $this->is_event_admin = true;
            }
            else
            {
                if($this->user->isInGroupNamed('ArtAdmins'))
                {
                    $this->is_admin       = true;
                    $this->is_art_admin   = true;
                }
                if($this->user->isInGroupNamed('CampAdmins'))
                {
                    $this->is_admin       = true;
                    $this->is_tc_admin    = true;
                }
                if($this->user->isInGroupNamed('DMVAdmins'))
                {
                    $this->is_admin       = true;
                    $this->is_dmv_admin   = true;
                }
                if($this->user->isInGroupNamed('EventAdmins'))
                {
                    $this->is_admin       = true;
                    $this->is_event_admin = true;
                }
                if(!$this->is_art_admin && !$this->is_tc_admin && !$this->is_dmv_admin && !$this->is_event_admin)
                {
                    $this->user = false;
                }
            }
        }
        $this->content['header']['sidebar'] = array();
        $this->content['header']['sidebar']['Dashboard'] = array('icon' => 'fa-dashboard', 'url' => 'index.php');
        if($this->is_tc_admin)
        {
            $this->content['header']['sidebar']['Theme Camps'] = array('icon' => 'fa-bed', 'url' => 'tc.php');
        }
        if($this->is_art_admin)
        {
            $this->content['header']['sidebar']['Art Projects'] = array('icon' => 'fa-picture-o', 'url' => 'art.php');
        }
        if($this->is_dmv_admin)
        {
            $this->content['header']['sidebar']['Art Cars'] = array('icon' => 'fa-car', 'url' => 'dmv.php');
        }
        if($this->is_event_admin)
        {
            $this->content['header']['sidebar']['Events'] = array('icon' => 'fa-calendar', 'url' => 'evt.php');
        }
        if($this->is_admin)
        {
            $this->content['header']['sidebar']['Variables'] = array('icon' => 'fa-cog', 'url' => 'vars.php');
            $this->content['header']['sidebar']['PDFs/Emails'] = array('icon' => 'fa-file', 'url' => 'text.php');
        }
        $this->content['loginUrl'] = $this->secure_root.'api/v1/login';
    }

    public function isAdmin()
    {
        return ($this->is_admin === true || $this->is_tc_admin === true || $this->is_art_admin === true || $this->is_dmv_admin === true || $this->is_event_admin === true);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
