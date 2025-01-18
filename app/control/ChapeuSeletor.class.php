<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TModalForm;
use Adianti\Widget\Form\TText;

class ChapeuSeletor extends TPage
{
    private $form;
    /**
     * Método Construtor
     */
    public function __construct()
    {
        parent::__construct();

        // Creates the form
        $this->form = new TModalForm('form_chapeu');
        $this->form->setFormTitle('Descobra sua Casa 🧙‍♂️');

        // Create the form fields
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
        $casas = ['Grifinória', 'Sonserina', 'Lufa-Lufa', 'Corvinal'];
        $casaEscolhida = $casas[array_rand($casas)];

        $message = "Hummmmmm... {$nome},  <br>
                    Interessante... sua casa será... <br>
                    <b>{$casaEscolhida}</b>!!!";

        if (empty($nome)) {
            new TMessage('error', 'Ops! Você precisa digitar seu nome para ser escolhido!');
        } else {
            $action = new TAction([$this, 'showToast'], ['nome' => $nome, 'casa' => $casaEscolhida]);
            new TMessage('info', $message, $action);
        }
    }

    /**
     *  Método onSuccess()
     *  Chegaaa
     */
    public function showToast($param)
    {
        $nome = $param['nome'];
        $casaEscolhida = $param['casa'];
        if ($casaEscolhida == 'Grifinória') {
            TToast::show('error', "Parabéns! $nome, sua casa será <b>Grifinória!</b>", 'top right', 'fa:hat-wizard');
        } else if ($casaEscolhida == 'Lufa-Lufa'){
            TToast::show('warning', "Parabéns! $nome, sua casa será <b>Lufa-Lufa</b>!", 'top right', 'fa:hat-wizard');
        } else if ($casaEscolhida == 'Corvinal'){
            TToast::show('info', "Parabéns! $nome, sua casa será <b>Corvinal!</b>", 'top right', 'fa:hat-wizard');
        } else if ($casaEscolhida == 'Sonserina'){
            TToast::show('success', "Parabéns! $nome, sua casa será <b>Sonserina!</b>", 'top right', 'fa:hat-wizard');
        }
    }
}