<?php

trait CampToVarArray
{
    public function getVariablesFromCamp($camp)
    {
        if(is_array($camp))
        {
            $camp = $this->toObject($camp);
        }
        $vars = array();
        $this->setVar($vars, $camp, '${campId}', 'location');
        $this->setVar($vars, $camp, '${coords}', 'coords');
        $this->setVar($vars, $camp->num, '${campers}', 'campers');
        if(isset($camp->prevInfo))
        {
          $vars['${prior}'] = 'Prior Camp Name: '.$camp->prevInfo->name.' ('.$camp->prevInfo->campers.')';
        }
        else
        {
          $vars['${prior}'] = 'New Camp';
        }
        if(isset($camp->structs))
        {
            $structCount = count($camp->structs->type);
            $vars['${structsTable}'] = '<table>';
            for($i = 0; $i < $structCount; $i++)
            {
                $vars['${structsTable}'].= '<tr><td>'.$camp->structs->desc[$i].'</td><td>'.$camp->structs->width[$i].'x'.$camp->structs->length[$i].'x'.$camp->structs->height[$i].'</td></tr>';
            }
            $vars['${structsTable}'].= '</table>';
            unset($camp->structs);
        }
        else
        {
            $vars['${structsTable}'] = '<i>No registered Structures</i>';
        }
        $objVars = get_object_vars($camp);
        foreach($objVars as $key=>$value)
        {
            if(is_object($value))
            {
                switch($key)
                {
                  case '_id':
                    $vars['${_id}'] = $value->{'$id'};
                    break;
                  default:
                    $childVars = get_object_vars($value);
                    foreach($childVars as $c_key=>$c_value)
                    {
                       $vars['${'.$key.'.'.$c_key.'}'] = $c_value;
                    }
                }
            }
            else
            {
                $vars['${'.$key.'}'] = $value;
            }
        }
        $vars['${image_table}'] = $this->getCampImageTableBody($camp);
        return $vars;
    }

    protected function setVar(&$vars, &$array, $subName, $arrayName, $default='<i>Unspecified</i>')
    {
        $vars[$subName] = $default;
        if(isset($array->$arrayName))
        {
            $vars[$subName] = $array->$arrayName;
            unset($array->$arrayName);
        }
    }

    protected function getCampImageTableBody($camp)
    {
        $count = 0;
        $html = '<tbody><tr><td>';
        if($camp->has->heavy)
        {
            $count++;
            $html.= '<img class="campType" src="https://secure.burningflipside.com/register/image1.png">';
        }
        if($camp->has->burnable)
        {
            $count++;
            $html.= '<img class="campType" src="https://secure.burningflipside.com/register/image2.png">';
        }
        if($camp->has->sex)
        {
            $count++;
            $html.= '<img class="campType" src="https://secure.burningflipside.com/register/image3.png">';
        }
        if($count == 3)
        {
            $html.= '</td></tr><tr><td>';
        }
        if($camp->has->sound)
        {
            $count++;
            $html.= '<img class="campType" src="https://secure.burningflipside.com/register/image4.png">';
        }
        if($count == 3)
        {
            $html.= '</td></tr><tr><td>';
        }
        if($camp->has->kids)
        {
            $count++;
            $html.= '<img class="campType" src="https://secure.burningflipside.com/register/image6.png">';
        }
        $html.= '</td></tr></tbody>';
        return $html;
    }

    protected function getSourceFromVarName($var_name)
    {
        $dataTable = \DataSetFactory::getDataTableByNames('registration', 'textStrings');
        $ret = $dataTable->read(new \Data\Filter('name eq '.$var_name));
        if($ret === false || empty($ret))
        {
            return false;
        }
        return $ret[0]['text'];
    }

    protected function toObject($arr)
    {
       $obj = new stdClass;
       foreach($arr as $key => $value)
       {
          if($key === 'structs')
          {
              $tmp = new stdClass;
              $tmp->type = $value['type'];
              $tmp->desc = $value['desc'];
              $tmp->width = $value['width'];
              $tmp->length = $value['length'];
              $tmp->height = $value['height'];
              $value = $tmp;
          }
          else if(is_array($value))
          {
              $value = $this->toObject($value);
          }
          $obj->{$key} = $value;
       }
       return $obj;
    }
}

class CampRegistrationPDF extends \Serialize\Serializer
{
    use CampToVarArray;

    protected function campToHtml($camp)
    {
        $source = $this->getSourceFromVarName('campPDF');
        $vars = $this->getVariablesFromCamp($camp);
        $html = strtr($source, $vars);
        return $html;
    }

    public function serializeData(&$type, $array)
    {
        $type = 'text/html';
        $html = '';
        if(is_array($array))
        {
            $count = count($array);
            for($i = 0; $i < $count; $i++)
            {
                $html.=$this->campToHtml($array[$i]);
            }
        }
        else
        {
            $html = $this->campToHtml($array);
        }
        return $html;
    }
}

class CampRegistrationEmail extends \Email\Email
{
    use CampToVarArray;

    public function __construct($camp)
    {
        parent::__construct();
        $this->addToAddress($camp['campLead']['email'], $camp['campLead']['name']);
        if(isset($camp['cleanupLead']) && strlen($camp['cleanupLead']['email']) > 0)
        {
            $this->addCCAddress($camp['cleanupLead']['email']);
        }
        if(isset($camp['safetyLead']) && strlen($camp['safetyLead']['email']) > 0)
        {
            $this->addCCAddress($camp['safetyLead']['email']);
        }
        if(isset($camp['volunteering']) && strlen($camp['volunteering']['email']) > 0)
        {
            $this->addCCAddress($camp['volunteering']['email']);
        }
        $this->camp = $camp;
    }

    public function getSubject()
    {
        return 'Burning Flipside Theme Camp Registration';
    }

    private function getBodyFromDB($html=true)
    {
        $source = $this->getSourceFromVarName('campEmail');
        $vars = $this->getVariablesFromCamp($this->camp);
        if($html === true)
        {
            return strtr($source, $vars);
	}
        else
        {
            $index = strpos($source, "<script");
            if($index !== false)
            {
                $end = strpos($source, "</script>");
                if($index === 0)
                {
                    $raw_text = substr($source, $end+9);
                }
            }
            return strtr(strip_tags($source), $vars);
        }
    }

    public function getHTMLBody()
    {
        return $this->getBodyFromDB();
    }

    public function getTextBody()
    {
        return $this->getBodyFromDB(false);
    }
}
