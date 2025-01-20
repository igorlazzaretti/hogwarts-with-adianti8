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

class MateriasEdit extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TModalForm('form_materia');
        $this->form->setFormTitle('Editar Matéria');

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $ano = new TCombo('ano');

        $id->setEditable(false);

        // Adiciona opções para o campo 'ano'
        $ano->addItems([
            1 => '1º Ano',
            2 => '2º Ano',
            3 => '3º Ano',
            4 => '4º Ano',
        ]);

        $ano->setValue('1');
        $id->setEditable(false);

        $this->form->addRowField('ID',   $id,   true);
        $this->form->addRowField('Nome', $nome, true);
        $this->form->addRowField('Ano',  $ano,  true);

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

                $materia = new Materia($id); // carrega o aluno do banco de dados

                if ($materia) {
                    $this->form->setData($materia); // preenche o formulário com os dados do aluno
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

            $materia = new Materia;
            $materia->fromArray((array) $data);
            $materia->store(); // armazena o aluno no banco de dados

            TTransaction::close(); // fecha a transação

            new TMessage('info', 'Matéria atualizada com sucesso!', new TAction([$this, 'onSuccess']));
            TToast::show('success', 'Matéria salvo com sucesso!', 'top center', 'fa:circle-check');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Materias', 'onReload');
    }

}