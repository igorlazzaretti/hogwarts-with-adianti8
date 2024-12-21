<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class AlunoStore extends TPage
{
    public function __construct()
    {
        try {
            // Abre transação com o banco 'hogwarts_school'
            TTransaction::open('hogwarts_school');

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}