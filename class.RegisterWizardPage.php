<?php
require_once('class.SecurePage.php');
class RegisterWizardPage extends SecurePage
{
    private $reg_type_long;
    private $reg_type_short;
    private $steps;

    function __construct($reg_type_long, $reg_type_short = FALSE)
    {
        parent::__construct('Burning Flipside - '.$reg_type_long.' Registration', true);
        if($reg_type_short == FALSE)
        {
            $this->reg_type_short = $reg_type_long;
        }
        else
        {
            $this->reg_type_short = $reg_type_short;
        }
        $this->steps = array(
            array('name'=>'Public Information', 
                  'content'=>array(
                      array('type'=>'form-group', 
                            'label'=>$this->reg_type_short.' Name', 
                            'name'=>'name',
                            'required'=>'true',
                            'control'=>array('type'=>'text')
                      ),
                      array('type'=>'spacer'),
                      array('type'=>'form-group',
                            'label'=>$this->reg_type_short.' Logo',
                            'name'=>'logo',
                            'control'=>array('type'=>'file')
                      ),
                      array('type'=>'spacer'),
                      array('type'=>'form-group',
                            'label'=>$this->reg_type_short.' Website',
                            'name'=>'site',
                            'control'=>array('type'=>'text')
                      ),
                      array('type'=>'spacer'),
                      array('type'=>'form-group',
                            'label'=>'One Line Teaser',
                            'name'=>'teaser',
                            'required'=>'true',
                            'control'=>array('type'=>'text')
                      ),
                      array('type'=>'spacer'),
                      array('type'=>'form-group',
                            'label'=>'Description',
                            'name'=>'description',
                            'required'=>'true',
                            'control'=>array('type'=>'textarea')
                      ),
                      array('type'=>'spacer')
                  )
            )
        );
        $this->reg_type_long = $reg_type_long;
        $this->add_script();
    }

    function add_script()
    {
        $this->addJS(JS_BOOTBOX, false);
        $this->add_js_from_src('js/reg.js', false);
    }

    function add_wizard_step($name, $id = FALSE)
    {
        $step = array('name'=>$name);
        if($id != FALSE)
        {
            $step['id'] = $id;
        }
        return array_push($this->steps, $step)-1;
    }

    private function &get_container($index)
    {
        if(is_array($index))
        {
            $count = count($index);
            $container = &$this->steps[$index[0]];
            for($i = 1; $i < $count; $i++)
            {
                $container = &$container['content'][$index[$i]];
            }
        }
        else
        {
            $container = &$this->steps[$index];
        }
        return $container;
    }

    function add_form_group($index, $label, $name, $type= 'text', $tooltip = FALSE, $extra = FALSE)
    {
        $container = &$this->get_container($index);
        if(!isset($container['content']))
        {
            $container['content'] = array();
        }
        $form_group = array('type'=>'form-group', 'label'=>$label, 'name'=>$name);
        $control = array('type'=>$type);
        if($tooltip != FALSE)
        {
            $control['tooltip'] = $tooltip;
        }
        if($extra != FALSE)
        {
            if(isset($extra['required']))
            {
                $form_group['required'] = $extra['required'];
                unset($extra['required']);
            }
            $control = array_merge($extra, $control);
        }
        $form_group['control'] = $control;
        array_push($container['content'], $form_group);
    }

    function add_spacer($index)
    {
        $container = &$this->get_container($index);
        if(!isset($container['content']))
        {
            $container['content'] = array();
        }
        array_push($container['content'], array('type'=>'spacer'));
    }

    function add_accordion($index)
    {
        $container = &$this->get_container($index);
        if(!isset($container['content']))
        {
            $container['content'] = array();
        }
        $ret = array_push($container['content'], array('type'=>'accordion'));
        return array($index, $ret-1);
    }

    function add_accordion_panel($index, $heading, $id = FALSE)
    {
        $container = &$this->get_container($index);
        if(!isset($container['content']))
        {
            $container['content'] = array();
        }
        if($id === FALSE)
        {
            $id = self::camelize($heading);
        }
        $ret = array_push($container['content'], array('type'=>'panel', 'heading'=>$heading, 'id'=>$id));
        array_push($index, $ret-1);
        return $index;
    }

    function add_raw_html($index, $html)
    {
        $container = &$this->get_container($index);
        if(!isset($container['content']))
        {
            $container['content'] = array();
        }
        array_push($container['content'], array('type'=>'raw', 'html'=>$html));
    }

    function print_tabs()
    {
        $ret = '';
        $max = count($this->steps);
        for($i = 0; $i < $max; $i++)
        {
            $ret.='<li';
            if($i == 0)
            {
                $ret.=' class="active"';
            }
            if(isset($this->steps[$i]['id']))
            {
                $ret.=' id="'.$this->steps[$i]['id'].'"';
            }
            $ret.='><a href="#tab'.$i.'" data-toggle="tab">'.$this->steps[$i]['name'].'</a></li>';
        }
        return $ret;
    }

    function print_form_input_control($control, $name, $required)
    {
        $ret = '<input class="form-control';
        if(isset($control['class']))
        {
            $ret.=' '.$control['class'];
        }
        $ret.='" type="'.$control['type'].'" name="'.$name.'" id="'.$name.'"';
        if(isset($control['tooltip']))
        {
            $ret.=' data-toggle="tooltip" data-placement="top" title="'.$control['tooltip'].'"';
        }
        if($required)
        {
            $ret.=' required';
        }
        if(isset($control['disabled']) && $control['disabled'])
        {
            $ret.=' disabled';
        }
        foreach($control as $key=>$value)
        {
            if(substr($key, 0, 5) == 'data-')
            {
                $ret.=' '.$key.'="'.$value.'"';
            }
        }
        $ret.='/>';
        return $ret;
    }

    function print_form_textarea_control($control, $name, $required)
    {
        $ret = '<textarea class="form-control" rows="6" name="'.$name.'" id="'.$name.'"';
        if(isset($control['tooltip']))
        {
            $ret.=' data-toggle="tooltip" data-placement="top" title="'.$control['tooltip'].'"';
        }
        if($required)
        {
            $ret.=' required';
        }
        $ret.='></textarea>';
        return $ret;
    }

    function print_form_select_control($control, $name, $required)
    {
        $ret = '<select class="form-control" name="'.$name.'" id="'.$name.'"';
        if(isset($control['tooltip']))
        {
            $ret.=' data-toggle="tooltip" data-placement="top" title="'.$control['tooltip'].'"';
        }
        if($required)
        {
            $ret.=' required';
        }
        $ret.='>';
        if(isset($control['options']))
        {
            $count = count($control['options']);
            for($i = 0; $i < $count; $i++)
            {
                $ret.='<option value="'.$control['options'][$i]['value'].'"';
                if(isset($control['options'][$i]['selected']) && $control['options'][$i]['selected'])
                {
                    $ret.=' selected';
                }
                $ret.='>'.$control['options'][$i]['text'].'</option>';
            }
        }
        $ret.='</select>';
        return $ret;
    }

    function print_form_group($group)
    {
        $required = FALSE;
        if(isset($group['required']))
        {
            $required = $group['required'];
        }
        $ret='<div class="form-group"><label for="'.$group['name'].'" class="col-sm-2 control-label';
        if(!$required)
        {
            $ret.=' non-required';
        }
        $ret.='">'.$group['label'].'</label>';
        $ret.='<div class="col-sm-10">';
        switch($group['control']['type'])
        {
            case 'text':
            case 'checkbox':
            case 'file':
                $ret.=$this->print_form_input_control($group['control'], $group['name'], $required);
                break;
            case 'textarea':
                $ret.=$this->print_form_textarea_control($group['control'], $group['name'], $required);
                break;
            case 'select':
                $ret.=$this->print_form_select_control($group['control'], $group['name'], $required);
                break;
            default:
                $ret.='Error: Don\'t know how to render control type: '.$group['control']['type'];
        }
        $ret.='</div></div>';
        return $ret;
    }

    function print_panel($panel, $selected = FALSE)
    {
       $ret = '<div class="panel panel-default';
       $ret.='"><div class="panel-heading" role="tab" id="'.$panel['id'].'Heading"><h4 class="panel-title"><a ';
       if(!$selected)
       {
           $ret.=' class="collapsed"';
       }
       $ret.='data-toggle="collapse" href="#'.$panel['id'].'" aria-expanded="true" aria-controls="'.$panel['id'].'">';
       $ret.=$panel['heading'];
       $ret.='</a></h4></div><div id="'.$panel['id'].'" class="panel-collapse collapse';
       if($selected)
       {
           $ret.=' in';
       }
       $ret.='" role="tabpanel" aria-labelledby="'.$panel['id'].'Heading"><div class="panel-body"></div>';
       $ret.=$this->print_container_content($panel);
       $ret.='</div></div>';
       return $ret;
    }

    function print_accordion($accordion)
    {
        $ret ='<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
        $max = count($accordion['content']);
        for($i = 0; $i < $max; $i++)
        {
            switch($accordion['content'][$i]['type'])
            {
                case 'panel':
                    $ret.=$this->print_panel($accordion['content'][$i], $i == 0);
                    break;
                default:
                    $ret.='<div>Error: don\'t know how to render type: '.$accordion['content'][$i]['type'].'</div>';
            }
        }
        $ret.='</div>';
        return $ret;
    }

    function print_container_content($container)
    {
        if(!isset($container['content']))
        {
            if(isset($container['name']))
            {
                $name = $container['name'];
            }
            else if(isset($container['heading']))
            {
                $name = $container['heading'];
            }
            return 'Error panel '.$name.' has no content!';
        }
        $ret = '';
        $content = $container['content'];
        $max = count($content);
        for($i = 0; $i < $max; $i++)
        {
            switch($content[$i]['type'])
            {
                case 'form-group':
                    $ret.=$this->print_form_group($content[$i]);
                    break;
                case 'spacer':
                    $ret.='<div class="clearfix visible-sm visible-md visible-lg"></div>';
                    break;
                case 'accordion':
                    $ret.=$this->print_accordion($content[$i]);
                    break;
                case 'raw':
                    $ret.=$content[$i]['html'];
                    break;
                default:
                    $ret.='<div>Error: don\'t know how to render type: '.$content[$i]['type'].'</div>';
            }
        }
        return $ret;
    }

    function print_tab_content($index)
    {
        $container = $this->get_container($index);
        return $this->print_container_content($container);
    }

    function print_content()
    {
        $ret = '';
        $max = count($this->steps);
        for($i = 0; $i < $max; $i++)
        {
            $ret.='<div role="tabpanel" class="tab-pane';
            if($i == 0)
            {
                $ret.=' active';
            }
            $ret.='" id="tab'.$i.'">'.$this->print_tab_content($i).'</div>';
        }
        return $ret;
    }

    function print_page($header=true)
    {
        if(!FlipSession::isLoggedIn())
        {
        $this->body .= '
    <div id="content">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">You must <a href="https://profiles.burningflipside.com/login.php?return='.$this->current_url().'">log in <span class="glyphicon glyphicon-log-in"></span></a> to access the Burning Flipside Registration system!</h1>
            </div>
        </div>
    </div>
';
        }
        else
        {
        $this->body = '
        <div id="content">
            <div id="rootwizard">
                <div class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#wizard-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse" id="wizard-navbar-collapse-1">
                        <ul class="nav navbar-nav">'.$this->print_tabs().'</ul>
                    </div>
                </div>
                <div class="tab-content">'.$this->print_content().'</div>
                <nav>
                    <ul class="pager">
                        <li class="previous"><a href="#" onclick="prev_tab(event)"><span aria-hidden="true">&larr;</span> Previous</a></li>
                        <li class="next"><a href="#" onclick="next_tab(event)">Save and Continue <span aria-hidden="true">&rarr;</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>';
        }
        parent::print_page($header);
    }

    static function camelize($value)
    {
        return lcfirst(strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => '')));
    }
}
?>
