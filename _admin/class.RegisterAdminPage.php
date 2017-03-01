<?php
require_once('class.FlipSession.php');
class RegisterAdminPage extends FlipAdminPage
{
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
    }

    function add_links()
    {
        $this->addLink('<span class="glyphicon glyphicon-dashboard"></span> Dashboard', 'index.php');
        if($this->is_tc_admin)
        {
            $this->addLink('<span class="glyphicon glyphicon-tent"></span> Theme Camps', 'tc.php');
        }
        if($this->is_art_admin)
        {
            $this->addLink('<span class="glyphicon glyphicon-blackboard"></span> Art Projects', 'art.php');
        }
        if($this->is_dmv_admin)
        {
            $this->addLink('<span class="glyphicon glyphicon-road"></span> Art Cars', 'dmv.php');
        }
        if($this->is_event_admin)
        {
            $this->addLink('<span class="glyphicon glyphicon-glass"></span> Events', 'dmv.php');
        }
        if($this->is_admin)
        {
            $this->addLink('<span class="fa fa-cog"></span> Variables', 'vars.php');
        }
    }

    public function isAdmin()
    {
        return ($this->is_admin === true || $this->is_tc_admin === true || $this->is_art_admin === true || $this->is_dmv_admin === true || $this->is_event_admin === true);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
