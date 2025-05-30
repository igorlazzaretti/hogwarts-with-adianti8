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

class AlunosEdit extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TModalForm('form_alunos_edit');
        $this->form->setFormTitle('Editar Aluno');

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $idade = new TEntry('idade');
        $casa = new TCombo('casa');
        $ano = new TCombo('ano');

        $id->setEditable(false);

        // Adiciona opções para o campo 'casa'
        $casa->addItems([
            'Grifinória' => 'Grifinória',
            'Sonserina' => 'Sonserina',
            'Corvinal' => 'Corvinal',
            'Lufa-Lufa' => 'Lufa-Lufa'
        ]);

        // Adiciona opções para o campo 'ano'
        $ano->addItems([
            1 => '1º Ano',
            2 => '2º Ano',
            3 => '3º Ano',
            4 => '4º Ano',
        ]);

        $ano->setValue('1');
        $casa->setValue('Grifinória');

        $id->setEditable(false);

        $this->form->addRowField('ID',    $id,   true);
        $this->form->addRowField('Nome',  $nome, true);
        $this->form->addRowField('Idade', $idade,true);
        $this->form->addRowField('Casa',  $casa, true);
        $this->form->addRowField('Ano',   $ano,  true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');
        $this->form->addFooterAction('Voltar', new TAction([$this, 'onSuccess']), 'fa:arrow-left');

        // add the form to the page
        parent::add($this->form);
    }

    public function onEdit($param)
    {
        try {
            if (isset($param['id'])) {
                $id = $param['id'];

                TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

                $aluno = new Aluno($id); // carrega o aluno do banco de dados

                if ($aluno) {
                    $this->form->setData($aluno); // preenche o formulário com os dados do aluno
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

            $aluno = new Aluno;
            $aluno->fromArray((array) $data);
            $aluno->store(); // armazena o aluno no banco de dados

            TTransaction::close(); // fecha a transação

            new TMessage('info', 'Aluno salvo com sucesso!', new TAction([$this, 'onSuccess']));
            TToast::show('success', 'Aluno salvo com sucesso!', 'top center', 'fa:circle-check');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Alunos', 'onReload');
    }

}