<?php

class CampRegistrationPDF extends \Serialize\Serializer
{
    protected $source = '
<style type="text/css">
tr {
  text-align: left
}

.campType {
  height: 64px;
  width: 64px;
}

@media print {
  footer {page-break-after: always;}
}
</style>
<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto; width:60%; float: left">
  <tr><th>Camp Number: ${campId}</th></tr>
  <tr><th>Map Coordinates: ${coords}</th></tr>
  <tr><th><h2>Camp Name: ${name}</h2></th></tr>
  <tr><td><i>${teaser}</i></td></tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto; width:40%; float: right">
  <tr><td><img class="campType" src="https://secure.burningflipside.com/register/image1.png">
          <img class="campType" src="https://secure.burningflipside.com/register/image2.png">
          <img class="campType" src="https://secure.burningflipside.com/register/image3.png"></td></tr>
  <tr><td><img class="campType" src="https://secure.burningflipside.com/register/image4.png">
          <img class="campType" src="https://secure.burningflipside.com/register/image5.png">
          <img class="campType" src="https://secure.burningflipside.com/register/image6.png"></td></tr>
</table>

<div><h3>Camp Information</h3>
Number of Campers: ${campers}<br>
${prior}</div>

<p><h3>CAMP LEAD INFO</h3>
Full Name: ${campLead.name}<br>
Burner Name: ${campLead.burnerName}<br>
Email Address: ${campLead.email}<br>
Phone Number: ${campLead.phone}</p>

<p><h3>PLACEMENT INFO</h3>
Preferences: ${placement.pref1}, ${placement.pref2}, ${placement.pref3}<br>
Placement Details: ${placement.desc}<br>
Special Considerations: ${placement.special}</p>

<p><h3>LIST OF STRUCTURES</h3>
Number of Standard Sized Tents: ${placement.tents}<br>

Other Structures<br>
${structsTable}

<p><strong>SOUND INFO</strong>
<p>Sound System Description: ${sound.desc}<br>
<p>Sound Hours: ${sound.from} - ${sound.to}</p>
<footer></footer>
';

    protected function campToHtml($camp)
    {
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
        $html           = strtr($this->source, $vars);
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

    protected function setVar(&$vars, &$array, $subName, $arrayName, $default='<i>Unspecified</i>')
    {
        $vars[$subName] = $default;
        if(isset($array->$arrayName))
        {
            $vars[$subName] = $array->$arrayName;
            unset($array->$arrayName);
        }
    }
}
