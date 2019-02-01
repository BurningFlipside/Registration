<?php
require_once('../class.SecureLoginRequiredPage.php');
class RegisterWizardPage extends SecureLoginRequiredPage
{
    private $reg_type_long;
    private $reg_type_short;
    private $steps;

    function __construct($reg_type_long, $reg_type_short = FALSE)
    {
        parent::__construct('Burning Flipside - '.$reg_type_long.' Registration', true);
        $this->addTemplateDir(dirname(__FILE__).'/templates', 'Register');
        $this->setTemplateName('@Register/reg.html');
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
        $this->addWellKnownJS(JS_BOOTBOX, false);
        $this->addJS('js/reg.js', false);
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

    function printPage()
    {
        $this->content['steps'] = $this->steps;
        parent::printPage();
    }

    static function camelize($value)
    {
        return lcfirst(strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => '')));
    }
}
