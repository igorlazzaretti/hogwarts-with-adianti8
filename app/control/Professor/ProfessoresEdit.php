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
use Adianti\Widget\Form\TText;
use Adianti\Wrapper\BootstrapFormBuilder;

class ProfessoresEdit extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->form = new TModalForm('form_edit');
        $this->form->setFormTitle('Editar Professor');

        $id = new TEntry('id');
        $id->setEditable(false);
        $id->placeholder = 'ID';


        $nome = new TEntry('nome');
        $nome->autofocus = 'autofocus';

        $materia = new TEntry('materia_id');
        $materia->setEditable(false);

        $curiosidade = new TText('curiosidade');

        $this->form->addRowField('ID do Professor:', $id,      true);
        $this->form->addRowField('Nome:',            $nome,    true);
        $this->form->addRowField('Matéria:',         $materia, true);
        $this->form->addRowField('Curiosidade:',     $curiosidade, true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']),    'fa:save');
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

                $professor = new Professor($id); // carrega o aluno do banco de dados

                if ($professor) {
                    $this->form->setData($professor); // preenche o formulário com os dados do aluno
                }

                // Matéria vinculada

                $conn = TTransaction::get(); // obtém a conexão ativa

                $stmt = $conn->prepare('SELECT nome FROM Materia WHERE id = :prof_id');
                $stmt->execute([':prof_id' => $param['id']]);
                $materia = $stmt->fetch();
                // setValue em $materia
                $materia_id = $this->form->getField('materia_id');
                $materia_id->setValue($materia['nome']);



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

            $professor = new Professor;
            $professor->fromArray((array) $data);
            $professor->store(); // armazena o aluno no banco de dados

            TTransaction::close(); // fecha a transação

            new TMessage('info', 'Professor atualizado(a) com sucesso!', new TAction([$this, 'onSuccess']));
            TToast::show('success', 'Professor atualizado(a) com sucesso', 'topt center', 'fa:circle-check');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Professores', 'onReload');
    }
}
