<?php
require_once('class.SecurePage.php');
require_once('class.FlipSession.php');
class RegisterAdminPage extends FlipPage
{
    public  $is_admin;
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
        $this->user = FlipSession::get_user(true);
        if($this->user !== false)
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
                    $this->is_art_admin   = true;
                }
                if($this->user->isInGroupNamed('CampAdmins'))
                {
                    $this->is_tc_admin    = true;
                }
                if($this->user->isInGroupNamed('DMVAdmins'))
                {
                    $this->is_dmv_admin   = true;
                }
                if($this->user->isInGroupNamed('EventAdmins'))
                {
                    $this->is_event_admin = true;
                }
                if(!$this->is_art_admin && !$this->is_tc_admin && !$this->is_dmv_admin && !$this->is_event_admin)
                {
                    $this->user = false;
                }
            }
        }
        parent::__construct($title);
        $this->add_js_from_src('/js/common/metisMenu.min.js');
        $this->add_js_from_src('js/admin.js');
        $this->add_css_from_src('/css/common/admin.css');
        $this->add_sites();
    }

    function add_sites()
    {
        $this->add_site('Profiles', 'http://profiles.burningflipside.com');
        $this->add_site('WWW', 'http://www.burningflipside.com');
        $this->add_site('Pyropedia', 'http://wiki.burningflipside.com');
        $this->add_site('Secure', 'https://secure.burningflipside.com');
    }

    function add_header()
    {
        $sites = '';
        foreach($this->sites as $link => $site_name)
        {
            $sites .= '<li><a href="'.$site_name.'">'.$link.'</a></li>';
        }
        $log = '';
        $tc  = '';
        $art = '';
        $dmv = '';
        $evt = '';
        if(!FlipSession::is_logged_in())
        {
            $log = '<a href="https://profiles.burningflipside.com/login.php?return='.$this->current_url().'"><span class="glyphicon glyphicon-log-in"></span></a>';
        }
        else
        {
            $log = '<a href="https://profiles.burningflipside.com/logout.php"><span class="glyphicon glyphicon-log-out"></span></a>';
        }
        if($this->is_tc_admin)
        {
            $tc = '<li><a href="tc.php"><span class="glyphicon glyphicon-tent"></span> Theme Camps</a></li>';
        }
        if($this->is_art_admin)
        {
            $art = '<li><a href="art.php"><span class="glyphicon glyphicon-blackboard"></span> Art Projects</a></li>';
        }
        if($this->is_dmv_admin)
        {
            $dmv = '<li><a href="dmv.php"><span class="glyphicon glyphicon-road"></span> Art Cars</a></li>';
        }
        if($this->is_event_admin)
        {
            $evt = '<li><a href="evt.php"><span class="glyphicon glyphicon-glass"></span> Events</a></li>';
        }
        $this->body = '<div id="wrapper">
                  <nav class="navbar navbar-default navbar-static-top" role=navigation" style="margin-bottom: 0">
                      <div class="navbar-header">
                          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                              <span class="sr-only">Toggle Navigation</span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                          </button>
                          <a class="navbar-brand" href="index.php">Registration</a>
                      </div>
                      <ul class="nav navbar-top-links navbar-right">
                           <a href="/register/">
                              <span class="glyphicon glyphicon-home"></span>
                           </a>
                          &nbsp;&nbsp;
                          '.$log.'
                          <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <span class="glyphicon glyphicon-link"></span>
                                  <b class="caret"></b>
                              </a>
                              <ul class="dropdown-menu dropdown-sites">
                                  '.$sites.'
                              </ul>
                          </li>
                      </ul>
                      <div class="navbar-default sidebar" role="navigation">
                          <div class="sidebar-nav navbar-collapse" style="height: 1px;">
                              <ul class="nav" id="side-menu">
                                  <li>
                                      <a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a>
                                  </li>
                                  '.$tc.$art.$dmv.$evt.'
                              </ul>
                          </div>
                      </div>
                  </nav>
                  <div id="page-wrapper" style="min-height: 538px;">'.$this->body.'</div></div>';
    }

    function current_url()
    {
        return 'http'.(isset($_SERVER['HTTPS'])?'s':'').'://'."{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    function print_page($header=true)
    {
        if($this->user == FALSE)
        {
            $this->body = '
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">You must <a href="https://profiles.burningflipside.com/login.php?return='.$this->current_url().'">log in <span class="glyphicon glyphicon-log-in"></span></a> to access the Burning Flipside Ticket system!</h1>
            </div>
        </div>';
        }
        parent::print_page($header);
    }
}
/* vim: set tabstop=4 shiftwidth=4 expandtab: */
?>
