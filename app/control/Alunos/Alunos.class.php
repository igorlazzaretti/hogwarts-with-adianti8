<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Database\TDatabase;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class Alunos extends TPage
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);

        // create the datagrid columns
        $id = new TDataGridColumn('id', 'Nro', 'left', '10%');
        $name  = new TDataGridColumn  ( 'name',  'Bruxo(a)',    'left',   '25%');
        $age  = new TDataGridColumn   ( 'age',   'Idade',       'left',   '20%');
        $year  = new TDataGridColumn  ( 'year',  'Ano Escolar', 'left',   '20%');
        $house  = new TDataGridColumn ( 'house', 'Casa',        'left',   '25%');


        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($age);
        $this->datagrid->addColumn($year);
        $this->datagrid->addColumn($house);

        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onView'],[
            'id'    => '{id}',
            'nome'  => '{name}',
            'idade' => '{age}',
            'casa'  => '{house}',
            'ano'   => '{year}'
        ]);
        $action2 = new TDataGridAction([$this, 'onSubject'],[
            'nome' => '{name}',
            'ano'  => '{year}'
        ]);
        $action3 = new TDataGridAction([$this, 'onDelete'],[
            'id'    => '{id}',
            'nome'  => '{name}',
        ]);
        $action4 = new TDataGridAction([$this, 'onEdit'],[
            'id'    => '{id}',
            'nome'  => '{name}',
        ]);

        // custom button presentation
        $action1->setUseButton(TRUE);
        $action2->setUseButton(TRUE);
        $action3->setUseButton(TRUE);
        $action4->setUseButton(TRUE);

        // add the actions to the datagrid
        $this->datagrid->addAction($action1, '', 'fa:search blue');
        $this->datagrid->addAction($action2, 'Matérias', 'fa:book purple');
        $this->datagrid->addAction($action3, '', 'fa:trash red');
        $this->datagrid->addAction($action4, '', 'fa:edit green');


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
                $item->id = $row['id'];
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
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array(__CLASS__, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead

        $name   = $param['name'];
        // shows a dialog to the user
        new TQuestion(('Quer mesmo deletar ' . $name . '?'), $action);

    }

    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key = $param['key']; // get the parameter $key
            TTransaction::open('hogwartsdb'); // open a transaction with database
            $conn = TTransaction::get(); // get the database connection

            // executa a query SQL para deletar o aluno
            $conn->exec("DELETE FROM aluno WHERE id = {$key}");

            TTransaction::close(); // close the transaction

            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Wizard deleted.'), $pos_action); // success message

            TToast::show('warning', 'Aluno deletado com sucesso!', 'bottom right', 'far:check-circle');

            // Chama o método onReload para recarregar a lista
            self::onReload();
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];

                // Redireciona para a página de edição com o ID do aluno
                AdiantiCoreApplication::gotoPage('AlunosEdit', 'onEdit', ['id' => $id]);
            } else {
                new TMessage('error', 'ID do aluno não fornecido.');
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onReload($param = null)
    {

        // creates the datagrid model
        $this->datagrid->clear();

        // Start Populatin Data
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $result = $conn->query('SELECT
                id, nome, idade, casa, ano FROM aluno ORDER BY id');

            foreach ($result as $row)
            {
                $item = new StdClass;
                $item->id = $row['id'];
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

    }
}