<?php

use Adianti\Control\TAction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TModalForm;

class ChapeuSeletor extends TPage
{
    private $form;

    public function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new TModalForm('form_chapeu');
        $this->form->setFormTitle('Descobra sua Casa 🧙‍♂️');

        // create the form fields
        $login = new TEntry('nome');
        $login->disableAutoComplete();
        $login->autofocus = 'autofocus';
        $this->form->addRowField('Digite seu Nome', $login, true);

        // Adiciona um campo TText ao formulário
        $descricao = new TText('descricao');
        $this->form->addRowContent('Agora a Professora Minerva MaGonagol iniciará a sua seleção...', $descricao, true);

        $this->form->addAction('Coloque o Chapéu Seletor na Cabeça e...', new TAction([$this, 'onSelection']), 'fa:hat');

        // add the form to the page
        parent::add($this->form);
    }

    public function onSelection($param)
    {
        // Obtém os dados do formulário
        $data = $this->form->getData();

        // Exibe uma mensagem com os dados do formulário
        $nome = $data->nome;

        // Código Gerador de Casa
        


        $message = "Hummmmmm... {$nome},  <br>
                    Interessante... sua casa será...
                    ... Lufa-Lufa!!!";
        $action = new TAction([$this, 'showToast'], ['nome' => $nome]);

        new TMessage('info', $message, $action);
    }

    /**
     *  Método onSuccess()
     *  Chegaaa
     */
    public function showToast($param)
    {
        $nome = $param['nome'];
        TToast::show('warning', "Parabéns! $nome, sua casa será Lufa-Lufa", 'top right', 'far:wand-magic-sparkles');
    }
}