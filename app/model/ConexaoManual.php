<?php



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

            $result = $conn->query('SELECT id, nome FROM professor ORDER BY id');

            foreach ($result as $row) 
            {
                print_r('Professor(a): ' .
                        $row['id'] . '- Nome: ' .
                        $row['nome'] .  ';<br>');
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
            
            TTransaction::close();
        
        } catch (Exception $e) {

            new TMessage('error', $e->getMessage());
        }
    }
}

?>