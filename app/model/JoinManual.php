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
        try {
            TTransaction::open('hogwartsdb'); // Substitua 'seu_banco' pela sua conexão

            // Cria os repositórios
            $professorRepository = new TRepository('professor');
            $materiaRepository = new TRepository('materia');

            // Cria os critérios
            $criteria = new TCriteria;
            $criteria->add(new TFilter('professor.materia_id', '=', 'materia.id')); // JOIN

            // Carrega os objetos
            $objetos = $professorRepository->load($criteria, FALSE, $materiaRepository);

            // Exibe os resultados
            if ($objetos) {
                foreach ($objetos as $objeto) {
                    $materia = $objeto->materia->nome; // Acessa o nome da matéria através do relacionamento
                    $professor = $objeto->nome;
                    echo "A matéria $materia é ministrada pelo professor $professor.<br>";
                }
            } else {
                echo "Nenhum resultado encontrado.";
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}