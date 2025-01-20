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

class TdcCadastrar extends TPage
{
    private $form;

    /**
     * Método Construtor: cria a página de Cadastro/Adição de Pontos
     */
    public function __construct()
    {
        parent::__construct();
        // creates the form
        $this->form = new TModalForm('form_alunos_cadastrar');
        $this->form->setFormTitle('Adicionar Pontos');

        // Adiciona os campos do formulário aqui
        $pontos = new TCombo('pontos');
        $pontos->addItems([
            '05'  => '05 pontos',
            '10'  => '10 pontos',
            '15'  => '15 pontos',
            '25'  => '25 pontos',
            '50'  => '50 pontos',
            '100' => '100 pontos',
        ]);
        $pontos->setValue('10');

        // Adiciona campo e opções para 'casa'
        $casa = new TCombo('casa');
        $casa->addItems([
            'Grifinória' => 'Grifinória',
            'Sonserina'  => 'Sonserina',
            'Corvinal'   => 'Corvinal',
            'Lufa-Lufa'  => 'Lufa-Lufa'
        ]);
        $casa->setValue('Lufa-Lufa');

        $this->form->addRowField([new TLabel('Idade')], $pontos,  true);
        $this->form->addRowField([new TLabel('Casa')],  $casa,   true);

        $this->form->addAction('Salvar', new TAction([$this, 'onSave']),    'fa:save');
        $this->form->addFooterAction('Voltar', new TAction([$this, 'onSuccess']), 'fa:arrow-left', 'btn-danger');

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
        if (empty($data->pontos) || empty($data->casa)) {
            throw new Exception('Todos os campos são obrigatórios.');
        }

        // Verifica se já existe um registro com a mesma casa
        $existingRecord = TacaCasas::where('casa', '=', $data->casa)->first();
        if ($existingRecord) {
            // Atualiza os pontos da casa existente
            $existingRecord->pontos += $data->pontos;
            $existingRecord->store();
        } else {
            // Cria um novo registro para a casa
            $formPoints = new TacaCasas;
            $formPoints->fromArray((array) $data);
            $formPoints->store(); // armazena o objeto no banco de dados
        }

        TTransaction::close(); // fecha a transação

        // coloca os dados de volta no formulário
        $this->form->setData($data);

        // cria uma string com os valores dos elementos do formulário
        $message  = "Você adicionou {$data->pontos} pontos para a casa {$data->casa}!";

        // exibe a mensagem
        new TMessage('info', $message, new TAction([$this, 'onSuccess']));

            // exibe um toast de confirmação
            TToast::show('success', 'Pontos adicionados com sucesso!', 'top right', 'fa:circle-check');
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
        AdiantiCoreApplication::gotoPage('TacaDasCasas', 'onReload');
    }

}
