<?php

use Adianti\Control\TPage;
use Adianti\Database\TDatabase;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class AlunosTotal extends TPage
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);

        // create the datagrid columns
        $name  = new TDataGridColumn( 'name',  'Bruxo(a)',    'left',   '25%');
        $age  = new TDataGridColumn(  'age',   'Idade',       'left',   '25%');
        $year  = new TDataGridColumn( 'year',  'Ano Escolar', 'left',   '25%');
        $house  = new TDataGridColumn('house', 'Casa',        'left',   '25%');


        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($age);
        $this->datagrid->addColumn($year);
        $this->datagrid->addColumn($house);

        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onView'],[
            'nome'=>'{name}',
            'idade' => '{age}',
            'casa'=> '{house}',
            'ano' => '{year}'
        ]);
        $action2 = new TDataGridAction([$this, 'onSubject'],[
            'nome'=>'{name}',
            'ano' => '{year}'
        ]);

        // custom button presentation
        $action1->setUseButton(TRUE);
        $action2->setUseButton(TRUE);

        // add the actions to the datagrid
        $this->datagrid->addAction($action1, 'Informações', 'fa:search blue');
        $this->datagrid->addAction($action2, 'Matérias', 'fa:book blue');


        // creates the datagrid model
        $this->datagrid->createModel();

        // Start Populatin Data
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $result = $conn->query('SELECT
                id, nome, idade, casa, ano FROM aluno ORDER BY id');

            foreach ($result as $row)
            {
                $item = new StdClass;
                $item->name = $row['nome'];
                $item->age = $row['idade'];
                $item->year = $row['ano'];
                $item->house = $row['casa'];
                $this->datagrid->addItem($item);
            }

            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }

        $panel = new TPanelGroup();
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter('Alunos de Hogwarts School of Wizardry and Witchcraft');

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);
        parent::add($vbox);
    }


    /**
     *  Método onView()
     *  Mostra as informações específicas daquele aluno
     */
    public static function onView($param)
    {
        // get the parameter and shows the message
        $name   = $param['name'];
        $age    = $param['idade'];
        $house  = $param['casa'];
        $year   = $param['ano'];

        new TMessage('info', "O Nome do Aluno é: <b>$name</b>, <br>
                                Idade: <b>$age</b> anos, <br>
                                Casa: <b>$house</b>, <br>
                                Ano Escolar: <b>$year</b>");
    }

    /**
     *  Método onSubject()
     *
     */
    public static function onSubject($param)
    {
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $year = $param['ano'];

            // Consulta as matérias do ano escolar correspondente
            $result2 = $conn->query("SELECT nome FROM materia WHERE ano = {$year} ORDER BY id");

            $subjects = [];
            foreach ($result2 as $row) {
                $subjects[] = $row['nome'];
            }

            if ($subjects) {
                $subjectList = implode('<br>', $subjects);
                new TMessage('info', "Matérias do aluno:<br>{$subjectList}");
            } else {
                new TMessage('info', "Nenhuma matéria encontrada para este ano escolar.");
            }

            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        } finally {
            TTransaction::close();
        }
}
}