<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Database\TDatabase;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\THBox;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class Funcionarios extends TPage
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);

        // create the datagrid columns
        $id   = new TDataGridColumn  ( 'id',    'Nro',                 'left',  '0%');
        $name = new TDataGridColumn  ( 'name',  'Nome do Funcionário', 'left',  '40%');
        $cargo = new TDataGridColumn ( 'cargo', 'Cargo que Ocupa',     'left',  '60%');

        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($cargo);

        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onView'],[
            'id'       =>  '{id}',
            'nome'     =>  '{name}',
            'cargo'    =>  '{cargo}'
        ]);
        // Mostra as funções do funcionário
        $action2 = new TDataGridAction([$this, 'onSubject'],[
            'nome'     =>  '{name}',
            'cargo'    =>  '{cargo}',
            'funcoes'  =>  '{funcoes}'
        ]);
        $action3 = new TDataGridAction([$this, 'onDelete'],[
            'id'       =>  '{id}',
            'nome'     =>  '{name}',
            'cargo'    =>  '{cargo}'
        ]);
        $action4 = new TDataGridAction([$this, 'onEdit'],[
            'id'       =>  '{id}',
            'nome'     =>  '{name}',
            'cargo'    =>  '{cargo}'
        ]);

        // custom button presentation
        $action1->setUseButton(TRUE);
        $action2->setUseButton(TRUE);
        $action3->setUseButton(TRUE);
        $action4->setUseButton(TRUE);

        // add the actions to the datagrid
        $this->datagrid->addAction($action1, '',        'fa:search blue' );
        $this->datagrid->addAction($action2, 'Funções', 'fa:book purple' );
        $this->datagrid->addAction($action3, '',        'fa:trash red'   );
        $this->datagrid->addAction($action4, '',        'fa:edit green'  );


        // creates the datagrid model
        $this->datagrid->createModel();

        // Start Populatin Data
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $result = $conn->query('SELECT
                id, nome, cargo, funcoes FROM funcionario ORDER BY id');

            foreach ($result as $row)
            {
                $item = new StdClass;
                $item->id    =  $row['id'];
                $item->name  =  $row['nome'];
                $item->cargo =  $row['cargo'];
                $item->funcoes =  $row['funcoes'];

                $this->datagrid->addItem($item);
            }

            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }

        // Cria o botão de cadastrar matéria
        $button = new TButton('cadastrar_funcionario');
        $button->setLabel('Cadastrar Funcionário');
        $button->setImage('fa:plus green');
        $button->setAction(new TAction([$this, 'onCreateFuncionario']), 'Cadastrar Novo Funcionário');


        $panel = new TPanelGroup();
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        // Adiciona o botão ao rodapé do painel
        $panel->addHeaderWidget(THBox::pack($button));
        $panel->addFooter('Funcionários - Hogwarts School of Witchcraft and Wizardry');

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

        // Registra o botão no formulário
        $this->form = new TForm('form_materias');
        $this->form->setFields([$button]);
        $vbox->add($this->form);

        parent::add($vbox);
    }

    /**
     *  Método onView()
     *  Cadastra um novo professor
     */
    public function onCreateFuncionario($param)
    {
        AdiantiCoreApplication::gotoPage('FuncionariosCadastrar', 'onCreate');
    }

    /**
     *  Método onView()
     *  Mostra as informações específicas daquele aluno
     */
    public static function onView($param)
    {
        // get the parameter and shows the message
        $name     = $param['name'];
        $cargo  = $param['cargo'];

        new TMessage('info', "  O Nome do Professor é: <b>$name</b>, <br>
                                sua matéria lecionada é: <b>$cargo</b>;" );
    }

    /**
     *  Método onSubject()
     *  Mostras as funções específicas do funcionário
     */
    public static function onSubject($param)
    {
        $name    = $param['nome'];
        $funcoes = $param['funcoes'];

        new TMessage('info', "Suas funções do(a) <b>" . $name . "</b>: <br> <b>"
            . $funcoes . "</b>");
    }

    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array(__CLASS__, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead

        $name = $param['name'];

        // shows a dialog to the user
        new TQuestion(('Quer mesmo deletar <b>' . $name . '</b>?'), $action);
    }

    /**
     *  Método Delete()
     *  Deleta um Funcionário
     */
    public function Delete($param)
    {
        try
        {
            // $key conterá o id da Matéria
            $key = $param['key']; // get the parameter $key
            TTransaction::open('hogwartsdb'); // open a transaction with database
            $conn = TTransaction::get(); // get the database connection

            // Executa a query SQL para deletar a Matéria
            $conn->exec("DELETE FROM funcionario WHERE id = {$key}");

            TTransaction::close(); // close the transaction

            TToast::show('warning', 'Funcionário(a) deletado(a) com sucesso!', 'bottom right', 'fa:circle-check');

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
                AdiantiCoreApplication::gotoPage('FuncionariosEdit', 'onEdit', ['id' => $id]);
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
                id, nome, cargo, funcoes FROM funcionario ORDER BY id');

            foreach ($result as $row)
            {
                $item = new StdClass;
                $item->id = $row['id'];
                $item->name = $row['nome'];
                $item->cargo = $row['cargo'];
                $item->funcoes = $row['funcoes'];

                $this->datagrid->addItem($item);
            }

            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}