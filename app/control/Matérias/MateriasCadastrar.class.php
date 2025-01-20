<?php

use Adianti\Control\TAction;
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

class MateriasCadastrar extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        // Cria a janela
        $this->form = new TModalForm('form_materias');
        $this->form->setFormTitle('Cadastrar Matéria');
        // Cria o formulário

        // Adicione os campos do formulário aqui
        $nome = new TEntry('nome');
        $ano = new TCombo('ano');
        $ano->addItems([
            '1' => '1º Ano',
            '2' => '2º Ano',
            '3' => '3º Ano',
            '4' => '4º Ano',
        ]);
        $ano->setValue('1');

        $this->form->addRowField('Nome', $nome, true);
        $this->form->addRowField('Ano', $ano, true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');
        $this->form->addFooterAction('Voltar', new TAction([$this, 'onSuccess']), 'fa:arrow-left');

        // Adiciona o formulário à janela
        parent::add($this->form);
    }

    public function onSave($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $this->form->validate(); // valida os dados do formulário

            $data = $this->form->getData(); // obtém os dados do formulário

            $materia = new Materia;
            $materia->fromArray((array) $data);
            $materia->store(); // armazena o objeto no banco de dados

            TTransaction::close(); // fecha a transação

            // coloca os dados de volta no formulário
            $this->form->setData($data);

            // cria uma string com os valores dos elementos do formulário
            $message  = 'Você cadastrou a matéria:  <br>';
            $message .= 'Nome: '  . $data->nome .  '<br>';
            $message .= 'Ano: '   . $data->ano .   '<br>';

            // exibe a mensagem
            new TMessage('info', $message, new TAction([$this, 'onSuccess']));

            // exibe um toast de confirmação
            TToast::show('success', 'Matéria cadastrada com sucesso!', 'bottom right', 'fa:circle-check');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
    /**
     *  Método onSuccess()
     *  Se matéria cadastrada com sucesso, recarrega a datagrid para o usuário
     */
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Materias', 'onReload');
    }
}