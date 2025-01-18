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
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto1.png", 'caption' => '1', 'class' => 'active'];
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto2.webp", 'caption' => '2', 'class' => ''];
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto3.jpg", 'caption' => '3', 'class' => ''];
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto4.jpg", 'caption' => '4', 'class' => ''];
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto5.jpg", 'caption' => '5', 'class' => ''];
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto6.webp", 'caption' => '6', 'class' => ''];
        $images[] = ['index' => '0', 'image' => "app/images/carrossel/foto7.png", 'caption' => '7', 'class' => ''];

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
