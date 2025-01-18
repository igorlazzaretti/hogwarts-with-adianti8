<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TForm;
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
        $this->datagrid->addColumn(new TDataGridColumn('task',  'Casa', 'left',   '30%'));
        $column = $this->datagrid->addColumn(new TDataGridColumn('percent', 'Pontos', 'center', '70%'));

        // define the transformer method over image
        $column->setTransformer(function ($percent, $object, $row) {
            $bar = new TProgressBar;
            $bar->setMask('~ <b>{value}</b> pontos');
            $bar->setValue($percent);

            // Define a classe da barra de progresso com base na casa
            switch ($object->task) {
                case 'Lufa-Lufa':
                    $bar->setClass('warning'); // Verde
                    break;
                case 'Grifinória':
                    $bar->setClass('danger');  // Vermelho
                    break;
                case 'Corvinal':
                    $bar->setClass('info');   // Azul
                    break;
                case 'Sonserina':
                    $bar->setClass('success'); // Amarelo
                    break;
                default:
                    $bar->setClass('primary'); // Azul escuro (padrão)
            }
            return $bar;
        });

        // creates the datagrid model
        $this->datagrid->createModel();




        $button = new TButton('dar_10_pontos_para_grifinória');
        $button->setLabel('10 Pontos para a Grifinória');
        $button->setImage('fa:plus green');
        $button->setAction(new TAction([$this, 'on10pontosParaAGrifinoria']), '10 Pontos para a Grifinória');



        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(('Taça Das Casas de Hogwarts'),
            $this->datagrid,
            'House Cup - Hogwarts School of Wizardry and Witchcraft'
        ));

        parent::add($vbox);
    }

    /**
     *  Método onReload()
     *  Load the data into the datagrid
     */
    function onReload()
    {
        $this->datagrid->clear();
        // Start Populatin Data
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $result = $conn->query('SELECT id, casa, pontos FROM tacadascasas ORDER BY id');

            foreach ($result as $row) {
                $item = new StdClass;
                $item->code    = $row['id'];
                $item->task    = $row['casa'];
                $item->percent = $row['pontos'];

                $this->datagrid->addItem($item);
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Function to add 10 points to Grifinória
     */
    public function on10pontosParaAGrifinoria()
    {
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $conn->exec("UPDATE tacadascasas SET pontos = pontos + 10 WHERE casa = 'Grifinória'");

            TTransaction::close();
            $this->onReload();
            new TMessage('info', '10 pontos adicionados para a Grifinória');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
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
