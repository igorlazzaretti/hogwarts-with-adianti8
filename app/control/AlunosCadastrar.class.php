<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TColor;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
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
    function __construct()
    {
        parent::__construct();

        // create the form
        $this->form = new BootstrapFormBuilder();
        $this->form->setFormTitle(('Cadastro de Alunos'));
        $this->form->generateAria(); // automatic aria-label

        // create the form fields
        $nome        = new TEntry('nome');
        $age       = new TEntry('idade');
        $ano        = new TCombo('ano');
        $casa        = new TCombo('casa');


        $age->setNumericMask(0, ',', '.', true);
        $age->setSize  ('45%');
        $ano->setSize  ('45%');
        $ano->addItems( [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4
            ]);
        $ano->setValue('1');
        $casa->setSize ('45%');
        $casa->addItems( [
            'Lufa-Lufa' => 'Lufa-Lufa',
            'Grifinória' => 'Grifinória',
            'Corvinal' => 'Corvinal',
            'Sonserina' => 'Sonserina'
            ]);


        $age->setValue(11);
        $casa->setValue('l');

        // add the fields inside the form
        $this->form->addFields( [new TLabel('Nome do Aluno')], [$nome] );
        $this->form->addFields( [new TLabel('Idade')],       [$age]);
        $this->form->addFields( [new TLabel('Ano Escolar')], [$ano]);
        $this->form->addFields( [new TLabel('Casa')],        [$casa]);


        $nome->placeholder = 'Digite o nome do aluno';
        $nome->setTip('Com base no primeiro filme, preferencialmente');


        // define the form action
        $this->form->addAction('Send', new TAction(array($this, 'onSend')), 'far:rocket orange');

        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        parent::add($vbox);
    }

    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSend($param)
    {
        try {
            TTransaction::open('hogwartsdb'); // abre uma transação com o banco de dados

            $data = $this->form->getData(); // obtém os dados do formulário

            // cria um novo objeto de aluno
            $aluno = new Aluno;
            $aluno->nome = $data->nome;
            $aluno->idade = $data->idade;
            $aluno->ano = $data->ano;
            $aluno->casa = $data->casa;

            $aluno->store(); // armazena o objeto no banco de dados

            TTransaction::close(); // fecha a transação

            // coloca os dados de volta no formulário
            $this->form->setData($data);

            // cria uma string com os valores dos elementos do formulário
            $message  = 'Você cadastrou o bruxo:<br>';
            $message .= 'Nome: ' . $data->nome . '<br>';
            $message .= 'Idade: ' . $data->idade . '<br>';
            $message .= 'Ano: ' . $data->ano . '<br>';
            $message .= 'Casa: ' . $data->casa . '<br>';

            // exibe a mensagem
            new TMessage('info', $message);

            // exibe um toast de confirmação
            TToast::show('success', 'Aluno cadastrado com sucesso!', 'bottom right', 'far:check-circle');
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback(); // desfaz a transação em caso de erro
        }
    }
}
