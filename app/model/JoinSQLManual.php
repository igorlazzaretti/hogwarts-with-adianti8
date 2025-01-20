<?php

use Adianti\Control\TPage;
use Adianti\Database\TRepository;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class JoinSQLManual extends TPage
{

    public function __construct() {
        parent::__construct();
        echo 'Join manual com SQL';
        echo '<br><br>';

        try {
            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $sql = "SELECT Professor.*, Materia.nome AS materia_nome
                    FROM Professor
                    INNER JOIN Materia ON Professor.materia_id = Materia.id";

            $result = $conn->query($sql);

            foreach ($result as $row) {
                echo $row['nome'] . ' - ' . $row['materia_nome'] . '<br>';
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}
