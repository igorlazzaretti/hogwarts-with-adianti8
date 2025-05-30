<?php

use Adianti\Database\TRecord;

class Materia extends TRecord
{
    const TABLENAME = 'materia';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('ano');
        parent::addAttribute('assunto');
    }
}