<?php

use Adianti\Control\TPage;
use Adianti\Database\TRepository;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class JoinManual extends TPage
{

    public function __construct() {
        parent::__construct();
        try {
        TTransaction::open('hogwartsdb');

        $repository = new TRepository('Professor'); // Repositório da tabela principal

        $criteria = new TCriteria;
        $criteria->add(new TFilter('professor.materia_id', '=', 'Materia.id')); // JOIN com a tabela Materia

        $objects = $repository->load($criteria); // Carrega os objetos Professor com o JOIN

        foreach ($objects as $object) {
            echo $object->nome . ' - ' . $object->materia->nome . '<br>'; // Acessa os dados da tabela Materia
        }

        TTransaction::close();
        } catch (Exception $e) {
        new TMessage('error', $e->getMessage());
        }
    }
}