<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Control\TWindow;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TModalForm;
use Adianti\Wrapper\BootstrapFormBuilder;

class FuncionariosEdit extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TModalForm('form_funcionario_edit');
        $this->form->setFormTitle('Editar Funcionário(a)');

        $id = new TEntry('id');
        $id->setEditable(false);

        $nome = new TEntry('nome');
        $nome->disableAutoComplete();
        $nome->autofocus = 'autofocus';

        $cargo = new TEntry('cargo');


        // // Create the form fields
        // $login = new TEntry('nome');
        // $login->disableAutoComplete();
        // $login->autofocus = 'autofocus';
        // $this->form->addRowField('Digite seu Nome', $login, true);


        $this->form->addRowField('ID do(a) Funcionário(a)', $id, true);

        $this->form->addRowField('Nome do(a) Funcionário(a)', $nome, true);

        $this->form->addRowField('Cargo que ocupa',  $cargo,  true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');
        $this->form->addFooterAction('Voltar', new TAction([$this, 'onSuccess']), 'fa:arrow-left');

        parent::add($this->form);
    }

    /**
     *  Método onEdit()
     *  É o método principal
     */
    public function onEdit($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];

                TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

                $funcionario = new Funcionario($id); // carrega o aluno do banco de dados

                if ($funcionario) {
                    $this->form->setData($funcionario); // preenche o formulário com os dados do aluno
                }

                TTransaction::close(); // fecha a transação
            }
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $this->form->validate(); // valida os dados do formulário

            $data = $this->form->getData(); // obtém os dados do formulário

            $funcionario = new Funcionario();
            $funcionario->fromArray((array) $data);
            $funcionario->store(); // armazena o aluno no banco de dados

            TTransaction::close(); // fecha a transação

            TToast::show('success', 'Funcionário(a) salvo com sucesso!', 'top right', 'fa:circle-check');
            self::onSuccess();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Funcionarios', 'onReload');
    }

}