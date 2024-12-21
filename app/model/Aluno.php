<?php

use Adianti\Database\TRecord;

class Aluno extends TRecord
{
    const TABLENAME = 'aluno';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('idade');
        parent::addAttribute('casa');
        parent::addAttribute('ano');
    }
}