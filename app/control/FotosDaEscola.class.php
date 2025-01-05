<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Template\THtmlRenderer;

class FotosDaEscola extends TPage
{
    /**
     *  Page constructor
     */
    function __construct()
    {
        parent::__construct();

        $images = [];
        $images   [] = ['index' => '0', 'image' => "app/images/hog1.webp", 'caption' => 'Image 1', 'class' => 'active'];
        $images   [] = ['index' => '0', 'image' => "app/images/hog1.webp", 'caption' => '', 'class' => ''];

        $html = new THtmlRenderer('app/resources/fotos-da-escola.html');
        $html->enableSection('main', []);
        $html->enableSection('indicator', $images, true);
        $html->enableSection('slide',     $images, true);

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        //$vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);

        parent::add($vbox);
    }
}
