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
use Adianti\Wrapper\BootstrapFormBuilder;

class MateriasEdit extends TWindow
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        parent::setSize(0.6, 0.5); // Define o tamanho da janela (60% da largura e 50% da altura)
        parent::setTitle('Editar Matéria'); // Define o título da janela

        $this->form = new BootstrapFormBuilder('form_materia');

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

        $this->form->addFields([new TLabel('ID')], [$id]);
        $this->form->addFields([new TLabel('Nome')], [$nome]);
        $this->form->addFields([new TLabel('Ano')], [$ano]);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:save');

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
            TToast::show('success', 'Aluno salvo com sucesso!', 'bottom right', 'far:check-circle');

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