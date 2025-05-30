<?php

use Adianti\Database\TRecord;

class Funcionario extends TRecord
{
    const TABLENAME = 'funcionario';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('cargo');
        parent::addAttribute('funcoes');
    }
}