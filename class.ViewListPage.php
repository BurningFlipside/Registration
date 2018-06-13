<?php
require_once('class.RegisterPage.php');
class ViewListPage extends RegisterPage
{
    function __construct($title)
    {
        parent::__construct($title, true);
        $this->addTemplateDir('./templates', 'Register');
        $this->setTemplateName('@Register/view-list.html');
    }
}
