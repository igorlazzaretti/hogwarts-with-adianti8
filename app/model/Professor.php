<?php

use Adianti\Database\TRecord;

class Professor extends TRecord
{
    const TABLENAME = 'professor';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('materia_id');
        parent::addAttribute('curiosidade');
    }
}