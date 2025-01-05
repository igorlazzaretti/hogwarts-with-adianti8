<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Util\TProgressBar;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class TacaDasCasas extends TPage
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';

        // add the columns
        $this->datagrid->addColumn( new TDataGridColumn('task',  'Casa', 'left',   '40%') );
        $column = $this->datagrid->addColumn( new TDataGridColumn('percent', 'Pontos', 'center', '40%') );

        // define the transformer method over image
        $column->setTransformer( function($percent) {
            $bar = new TProgressBar;
            $bar->setMask('~ <b>{value}</b> pontos');
            $bar->setValue($percent);

            if ($percent == 100) {
                $bar->setClass('warning');
            }
            else if ($percent >= 75) {
                $bar->setClass('danger');
            }
            else if ($percent >= 50) {
                $bar->setClass('info');
            }
            else {
                $bar->setClass('success');
            }
            return $bar;
        });

        // creates the datagrid model
        $this->datagrid->createModel();

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(('Taça Das Casas de Hogwarts'), $this->datagrid,
            'House Cup - Hogwarts School of Wizardry and Witchcraft'));
        parent::add($vbox);
    }

    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();

        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '1';
        $item->task      = 'Lufa-Lufa';
        $item->percent   = '100';
        $this->datagrid->addItem($item);

        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '2';
        $item->task      = 'Grifinória';
        $item->percent   = '80';
        $this->datagrid->addItem($item);

        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '3';
        $item->task      = 'Corvinal';
        $item->percent   = '60';
        $this->datagrid->addItem($item);

        // add an regular object to the datagrid
        $item = new StdClass;
        $item->code      = '4';
        $item->task      = 'Sonserina';
        $item->percent   = '40';
        $this->datagrid->addItem($item);
    }

    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
