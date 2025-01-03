<?php

use Adianti\Control\TPage;

class InicioDaJornada extends TPage
{
    private $html;

    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();

        $this->html = new THtmlRenderer('app/resources/welcome.html');

        // define replacements for the main section
        $replace = array();

        // replace the main section variables
        $this->html->enableSection('main', $replace);

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->html);

        // add the build to the page
        parent::add($container);
    }
}
