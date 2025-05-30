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

class AlunosCadastrar extends TPage
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
        $this->form = new TModalForm('form_alunos_cadastrar');
        $this->form->setFormTitle('Cadastrar Alunos');

        // Adicione os campos do formulário aqui
        $nome = new TEntry('nome');
        $age = new TCombo('idade');
        $age->addItems([
            '11' => '11 anos',
            '12' => '12 anos',
            '13' => '13 anos',
            '14' => '14 anos',
            '15' => '15 anos',
            '16' => '16 anos',
            '17' => '17 anos',
            '18' => '18 anos',
            '19' => '19 anos',
            '20' => '20 anos',
        ]);
        $age->setValue('11');

        $year = new TCombo('ano');
        $year->addItems([
            '1' => '1º Ano',
            '2' => '2º Ano',
            '3' => '3º Ano',
            '4' => '4º Ano',
        ]);
        $year->setValue('1');

        $house = new TCombo('casa');
        // Adiciona opções para o campo 'casa'
        $house->addItems([
            'Grifinória' => 'Grifinória',
            'Sonserina' => 'Sonserina',
            'Corvinal' => 'Corvinal',
            'Lufa-Lufa' => 'Lufa-Lufa'
        ]);
        $house->setValue('Lufa-Lufa');

        $this->form->addRowField('Nome:',  $nome,   true);
        $this->form->addRowField('Idade:', $age,    true);
        $this->form->addRowField('Ano:',   $year,   true);
        $this->form->addRowField('Casa:',  $house,  true);

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


            // Verifica se todos os campos obrigatórios estão preenchidos
            if (empty($data->nome) || empty($data->idade) || empty($data->ano) || empty($data->casa)) {
                throw new Exception('Todos os campos são obrigatórios.');
            }

            $alunos = new Aluno;
            $alunos->fromArray((array) $data);
            $alunos->store(); // armazena o objeto no banco de dados

            TTransaction::close(); // fecha a transação

            // coloca os dados de volta no formulário
            $this->form->setData($data);

            // cria uma string com os valores dos elementos do formulário
            $message  = 'Você cadastrou o(a) Aluno(a):  <br>';
            $message .= 'Nome: '    . $data->nome .  '<br>';
            $message .= 'Idade: '   . $data->idade . '<br>';
            $message .= 'Ano: '     . $data->ano .   '<br>';
            $message .= 'Casa: '    . $data->casa .  '<br>';

            // exibe a mensagem
            new TMessage('info', $message, new TAction([$this, 'onSuccess']));

            // exibe um toast de confirmação
            TToast::show('success', 'Aluno(a) cadastrado(a) com sucesso!', 'top right', 'fa:circle-check');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }

    /**
     *  Método onSuccess()
     *  Se Aluno cadastrado com sucesso, recarrega a datagrid para o usuário
     */
    public function onSuccess()
    {
        AdiantiCoreApplication::gotoPage('Alunos', 'onReload');
    }
}
