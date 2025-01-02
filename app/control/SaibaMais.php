<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Template\THtmlRenderer;
use Adianti\Widget\Util\TXMLBreadCrumb;

class SaibaMais extends TPage
{
    private $html;
    
    function __construct()
    {
        parent::__construct();
        
        $this->html = new THtmlRenderer('app/resources/saibamais.html');

        $replace = array();
        
        $this->html->enableSection('main', $replace);
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->html);
        
        // add the build to the page
        parent::add($container);
    }
}
