<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Core\AdiantiCoreApplication;
use Adianti\Database\TTransaction;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TColor;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Form\TModalForm;
use Adianti\Widget\Form\TPassword;
use Adianti\Widget\Util\TDropDown;
use Adianti\Widget\Util\TXMLBreadCrumb;
use Adianti\Wrapper\BootstrapFormBuilder;

class FuncionariosCadastrar extends TPage
{
    private $form;

    /**
     * Class constructor
     * Creates the page
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new TModalForm('form_funcionarios_cadastrar');
        $this->form->setFormTitle('Cadastrar Funcionário(a)');

        // Adicione os campos do formulário aqui
        $nome = new TEntry('nome');
        $cargo = new TEntry('cargo');

        $this->form->addRowField([new TLabel('Nome')],  $nome,   true);
        $this->form->addRowField([new TLabel('Cargo')], $cargo,  true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');

        // add the form to the page
        parent::add($this->form);
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $this->form->validate(); // valida os dados do formulário

            $data = $this->form->getData(); // obtém os dados do formulário


            // Verifica se todos os campos obrigatórios estão preenchidos
            if (empty($data->nome) || empty($data->cargo) ) {
                throw new Exception('Os campos nome e cargo são obrigatórios.');
            }

            $funcionarios = new Funcionario;
            $funcionarios->fromArray((array) $data);
            $funcionarios->store(); // armazena o objeto no banco de dados

            TTransaction::close(); // fecha a transação

            // coloca os dados de volta no formulário
            $this->form->setData($data);

            // cria uma string com os valores dos elementos do formulário
            $message  = 'Você cadastrou o(a) Aluno(a):  <br>';
            $message .= 'Nome: '    . $data->nome .    '<br>';
            $message .= 'Cargo: '   . $data->cargo .   '<br>';


            // exibe a mensagem
            new TMessage('info', $message, new TAction([$this, 'onSuccess']));

            // exibe um toast de confirmação
            TToast::show('success', 'Funcionário(a) cadastrado(a) com sucesso!', 'top right', 'far:check-circle');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }

    /**
     *  Método onSuccess()
     *  Se Funcionário cadastrado com sucesso, recarrega a datagrid para o usuário
     */
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Funcionarios', 'onReload');
    }
}
