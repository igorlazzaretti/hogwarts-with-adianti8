<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\THBox;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridActionGroup;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TButton;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class Materias extends TPage
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);

        // create the datagrid columns
        $id     = new TDataGridColumn('',      '',            'left',  '2%');
        $name   = new TDataGridColumn('name',  'Matéria',     'left',  '58%');
        $year   = new TDataGridColumn('year',  'Ano Escolar', 'left',  '40%');


        // add the columns to the datagrid, with actions on column titles, passing parameters
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($name);
        $this->datagrid->addColumn($year);

        // creates two datagrid actions
        $action1 = new TDataGridAction([$this, 'onView'], [
            'id'    => '{id}',
            'nome'  => '{name}',
            'ano'   => '{year}'
        ]);
        $action2 = new TDataGridAction([$this, 'onSubject'], [
            'nome' => '{name}',
            'ano'  => '{year}',
            'assunto'  => '{assunto}'
        ]);
        $action3 = new TDataGridAction([$this, 'onDelete'], [
            'id'    => '{id}',
            'nome'  => '{name}',
        ]);
        $action4 = new TDataGridAction([$this, 'onEdit'], [
            'id'    => '{id}',
            'nome'  => '{name}',
        ]);
        $action5 = new TDataGridAction([$this, 'onProfessor'], [
            'id'    => '{id}',
            'nome'  => '{name}',
        ]);

        // custom button presentation
        $action1->setUseButton(TRUE);
        $action2->setUseButton(TRUE);
        $action3->setUseButton(TRUE);
        $action4->setUseButton(TRUE);
        $action5->setUseButton(TRUE);

        // add the actions to the datagrid
        $this->datagrid->addAction($action1, '',         'fa:search blue');
        $this->datagrid->addAction($action2, 'Assunto',  'fa:book purple');
        $this->datagrid->addAction($action4, '',         'fa:edit green');
        $this->datagrid->addAction($action5, 'Professor', 'fa:hat-wizard orange');

        // Grupo de Ações
        $action1 = new TDataGridAction([$this, 'onDelete']);
        $action1->setField('id'); // Define the field for the action
        $action2 = new TDataGridAction([$this, 'onDeleteProfessor']);
        $action2->setField('id'); // Define the field for the action
        $action1->setLabel('Matéria');
        $action1->setImage('fa:trash red');
        $action2->setLabel('Professor');
        $action2->setImage('fa:trash red');
        $action_group = new TDataGridActionGroup('Excluir', 'fa:trash red');
        $action_group->addHeader('Excluir a Matéria');
        $action_group->addAction($action1);
        $action_group->addSeparator();
        $action_group->addHeader('Excluir todos os Professores da Matéria');
        $action_group->addAction($action2);
        $this->datagrid->addActionGroup($action_group);
        $this->datagrid->createModel();

        // creates the datagrid model
        $this->datagrid->createModel();

        // Start Populatin Data
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();
            
            $this->datagrid->clear();

            $result = $conn->query('SELECT
                id, nome, ano, assunto FROM materia ORDER BY id');

            foreach ($result as $row) {
                $item = new StdClass;
                $item->id = $row['id'];
                $item->name = $row['nome'];
                $item->year = $row['ano'];
                $item->assunto = $row['assunto'];

                $this->datagrid->addItem($item);
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }

        // Cria o botão de cadastrar matéria
        $button = new TButton('cadastrar_materia');
        $button->setLabel('Cadastrar Matéria');
        $button->setImage('fa:plus green');
        $button->setAction(new TAction([$this, 'onCreateMateria']), 'Cadastrar Matéria');


        $panel = new TPanelGroup();
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        // Adiciona o botão ao rodapé do painel
        $panel->addHeaderWidget(THBox::pack($button));
        $panel->addFooter('Matérias - Hogwarts School of Witchcraft and Wizardry');

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

    public function onCreateMateria($param)
    {
        AdiantiCoreApplication::gotoPage('MateriasCadastrar', 'onCreate');
    }

    /**
     *  Método onView()
     *  Mostra as informações específicas daquele aluno
     */
    public static function onView($param)
    {
        // get the parameter and shows the message
        $name   = $param['name'];
        $year   = $param['ano'];

        new TMessage('info', "  <br>
                                O Nome da Matéria é: <b>$name</b>, <br>
                                Ano Escolar: <b>$year</b>º ano;");
    }

    /**
     *  Método onSubject()
     *  Mostra o assunto ensinado nesta matéria
     */
    public static function onSubject($param)
    {
        $name    = $param['nome'];
        $assunto = $param['assunto'];

        new TMessage('info', "Assunto da Disciplina <b>" . $name . "</b>: <br> <b>"
            . $assunto . "</b>");
    }
    /**
     *  Método onProfessor()
     *  Mostra o professor que leciona a matéria
     */
    public static function onProfessor($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            // Obtém o ID da matéria a partir dos parâmetros
            $materia_id = $param['id'];

            $conn = TTransaction::get(); // obtém a conexão ativa

            $stmt = $conn->prepare('SELECT nome FROM Professor WHERE materia_id = :materia_id');
            $stmt->execute([':materia_id' => $materia_id]);

            $professores = $stmt->fetchAll();

            if ($professores) {
                $nomes_professores = '';
                foreach ($professores as $professor) {
                    $nomes_professores .= $professor['nome'] . ', ';
                }
                $nomes_professores = rtrim($nomes_professores, ', '); // Remove trailing comma and space
                new TMessage('info', 'Professor(es) encontrado(s) para a matéria: <b>'
                    . $nomes_professores . '.</b>');
            } else {
                new TMessage('info', 'Nenhum professor encontrado para esta matéria.');
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array(__CLASS__, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead

        $name = $param['name'];
        // shows a dialog to the user
        new TQuestion(('Quer mesmo deletar ' . $name . '?'), $action);
    }

    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try {
            // $key conterá o id da Matéria
            $key = $param['key']; // get the parameter $key
            TTransaction::open('hogwartsdb'); // open a transaction with database
            $conn = TTransaction::get(); // get the database connection

            // Executa a query SQL para deletar a Matéria
            $conn->exec("DELETE FROM materia WHERE id = {$key}");

            TTransaction::close(); // close the transaction

            TToast::show('warning', 'Matéria deletada com sucesso!', 'top right', 'fa:circle-check');

            // Chama o método onReload para recarregar a lista
            self::onReload();
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    public static function onDeleteProfessor($param)
    {
        // define the delete action
        $action = new TAction(array(__CLASS__, 'DeleteProfessor'));
        $action->setParameters($param); // pass the key parameter ahead

        // shows a dialog to the user
        new TQuestion(('Quer mesmo deletar ' . $param . '?'), $action);
    }

    /**
     * Delete a record
     */
    public function DeleteProfessor($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $this->form->validate(); // valida os dados do formulário

            $data = $param; // obtém os dados do formulário
            var_dump($data); // OK pega os dados do formulário

            $materia = new Materia;
            $materia->fromArray((array) $data);
            $materia->store(); // armazena o aluno no banco de dados

            var_dump($data); // OK pega os dados do formulário
            $conn = TTransaction::get();
            // ID do Professor
            $data_professor = $data->professor;
            // ID da Matéria
            $data_materia = $data->id;

            // Limpa todos os professores da matéria
            $clear = $conn->prepare('UPDATE Professor SET materia_id = NULL WHERE materia_id = :materia_id');
            $clear-> execute([':materia_id' => $data_materia]);

            TTransaction::close(); // fecha a transação

            new TMessage('info', 'Professores excluídos com sucesso!', new TAction([$this, 'onSuccess']));
            TToast::show('warning', 'Professores excluídos com sucesso!', 'top center', 'fa:circle-check');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];

                // Redireciona para a página de edição com o ID do aluno
                AdiantiCoreApplication::gotoPage('MateriasEdit', 'onEdit', ['id' => $id]);
            } else {
                new TMessage('error', 'ID do aluno não fornecido.');
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onReload($param = null)
    {

        // Clear the datagrid model
        $this->datagrid->clear();

        // Start Populatin Data
        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $result = $conn->query('SELECT
                id, nome, ano, assunto FROM materia ORDER BY id ');

            foreach ($result as $row) {
                $item = new StdClass;
                $item->id = $row['id'];
                $item->name = $row['nome'];
                $item->year = $row['ano'];
                $item->assunto = $row['assunto'];

                $this->datagrid->addItem($item);
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}
