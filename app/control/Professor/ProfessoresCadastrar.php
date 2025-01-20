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

class ProfessoresCadastrar extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new TModalForm('form_login');
        $this->form->setFormTitle('Cadastrar Professor');

        // Adicione os campos do formulário aqui
        $nome = new TEntry('nome');
        $materia_id = new TCombo('materia_id');
        try {
            TTransaction::open('hogwartsdb');
            $materias = Materia::getObjects(); // Retrieves all Materia records

            $items = [];
            foreach ($materias as $materia) {
                $items[$materia->id] = $materia->nome; // Key is ID, Value is Nome
            }
            $materia_id->addItems($items);

            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }

        $this->form->addRowField('Nome:', $nome,       true);
        $this->form->addRowField('Matéria:',  $materia_id, true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');
        $this->form->addFooterAction('Voltar', new TAction([$this, 'onSuccess']), 'fa:arrow-left');

        // add the form to the page
        parent::add($this->form);

    }

    public function onSave($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $this->form->validate(); // valida os dados do formulário

            $data = $this->form->getData(); // obtém os dados do formulário

            $materia_id = new Professor;
            $materia_id->fromArray((array) $data);
            $materia_id->store(); // armazena o objeto no banco de dados

            TTransaction::close(); // fecha a transação

            // coloca os dados de volta no formulário
            $this->form->setData($data);

            // cria uma string com os valores dos elementos do formulário
            $message  = 'Você cadastrou o(a) Professor(a):  <br>';
            $message .= 'Nome: '    . $data->nome .  '<br>';
            $message .= 'Responsável pela Matéria: '   . $data->materia_id .   '<br>';

            // exibe a mensagem
            new TMessage('info', $message, new TAction([$this, 'onSuccess']));

            // exibe um toast de confirmação
            TToast::show('success', 'Professor(a) cadastrado(a) com sucesso!', 'top right', 'fa:circle-check');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
    /**
     *  Método onSuccess()
     *  Se professor cadastrada com sucesso, recarrega a datagrid para o usuário
     */
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Professores', 'onReload');
    }
}