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

class MateriasEdit extends TWindow
{
    private $form;

    public function __construct()
    {
        parent::__construct();
        parent::setTitle('Janela');
        // parent::removePadding();
        parent::removeTitleBar();
        // parent::disableEscape();

        parent::setSize(0.6, null); // use 0.6, 0.4 (for relative sizes 60%, 40%)


        $this->form = new TModalForm('form_materia');
        $this->form->setFormTitle('Editar Matéria');

        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $ano = new TCombo('ano');
        $assunto = new TText('assunto');
        $professor_atual = new TEntry('professor_atual');
        $professor_atual->setEditable(false);


        $professor = new TCombo('professor');

        $id->setEditable(false);
        // Adiciona opções para o campo 'ano'
        $ano->addItems([
            1 => '1º Ano',
            2 => '2º Ano',
            3 => '3º Ano',
            4 => '4º Ano',
        ]);

        $ano->setValue('1');

        try {
            TTransaction::open('hogwartsdb');
            $professores = Professor::getObjects(); // Retrieves all Materia records

            $items = [];
            foreach ($professores as $m) {
                $items[$m->id] = $m->nome; // Key is ID, Value is Nome
            }
            $professor->addItems($items);

            TTransaction::close();

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }

        $this->form->addRowField('Número de Identificação', $id,        true);
        $this->form->addRowField('Nome',                    $nome,      true);
        $this->form->addRowField('Ano',                     $ano,       true);
        $this->form->addRowField('Assunto',                 $assunto,   true);
        $this->form->addRowField('Professor Atual:',        $professor_atual, true);
        $this->form->addRowField('Professor',               $professor,       true);

        $this->form->addAction(      'Salvar', new TAction([$this, 'onSave']),    'fa:save');
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

            }  // SETVALUE
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

                // Adiciona $nomes_professores ao campo 'professor_atual'
                $professor_atual = $this->form->getField('professor_atual');
                $professor_atual->setValue($nomes_professores);

            }


            TTransaction::close(); // fecha a transação

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }

    /**
     *  Método onSave()
     *  Salva os dados do formulário
     */
    public function onSave($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $this->form->validate(); // valida os dados do formulário

            $data = $this->form->getData(); // obtém os dados do formulário


            $materia = new Materia;
            $materia->fromArray((array) $data);
            $materia->store(); // armazena o aluno no banco de dados

            var_dump($data); // OK pega os dados do formulário
            $conn = TTransaction::get();
            // ID do Professor
            $data_professor = $data->professor;
            // ID da Matéria
            $data_materia = $data->id;
            // Atualiza o ID da Matéria na tabela Professor
            $stmt = $conn->prepare('UPDATE Professor SET materia_id = :materia_id WHERE id = :professor_id');
            $stmt->execute([':materia_id' => $data_materia, ':professor_id' => $data_professor]);

            TTransaction::close(); // fecha a transação

            new TMessage('info', 'Matéria atualizada com sucesso!', new TAction([$this, 'onSuccess']));
            TToast::show('success', 'Matéria salvo com sucesso!', 'top center', 'fa:circle-check');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }

    /**
     *  Método onSuccess()
     *  Redireciona para a página de listagem de alunos
     */
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Materias', 'onReload');
    }

    /**
     *  Método getProfessor()
     */
    public static function getProfessor($param)
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


            } else {
                new TMessage('info', 'Nenhum professor encontrado para esta matéria.');
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }


}