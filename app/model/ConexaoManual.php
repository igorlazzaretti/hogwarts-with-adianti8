<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ConexaoManual extends TPage
{
    public function __construct() {

        parent::__construct();


        try {

            TTransaction::open('hogwartsdb');
            $conn = TTransaction::get();

            $result = $conn->query('SELECT id, nome, idade, casa, ano FROM aluno ORDER BY id');

            foreach ($result as $row)
            {
                print_r('- ' .
                        $row['id'] . ' Nome: ' .
                        $row['nome'] . ', Idade: ' .
                        $row['idade'] . ', Casa: ' .
                        $row['casa'] . ', Ano Escolar: ' .
                        $row['ano'] . '°' . ' ano;' . '<br>');
            }

            print_r('<br> ');

            $result = $conn->query('SELECT id, nome, curiosidade FROM professor ORDER BY id');

            foreach ($result as $row)
            {
                print_r('Professor(a): ' .
                        $row['id'] . '- Nome: '  .
                        $row['nome'] .  ';<br>' .
                        $row['curiosidade'] .  ';<br>');
            }

            print_r('<br> ');

            $result = $conn->query('SELECT id, nome, ano FROM materia ORDER BY id');

            foreach ($result as $row)
            {
                print_r('Matéria ' .
                        $row['id'] . '- Nome: ' .
                        $row['nome'] . ', Ano: ' .
                        $row['ano'] . '°'. ';<br>');
            }

            /**
             *  Tabela: Funcionario
             *  id + nome + cargo
             */
            print_r('<br> ');

            $result = $conn->query('SELECT id, nome, cargo FROM funcionario ORDER BY id');

            foreach ($result as $row)
            {
                print_r('Funcionário ' .
                        $row['id'] . '- Nome: ' .
                        $row['nome'] . ', Ano: ' .
                        $row['cargo'] . ';<br>');
            }


            TTransaction::close();

        } catch (Exception $e) {

            new TMessage('error', $e->getMessage());
        }
    }
}

?>