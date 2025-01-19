<?php

use Adianti\Database\TRecord;

class TacaCasas extends TRecord
{
    const TABLENAME = 'tacadascasas';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('casa');
        parent::addAttribute('pontos');
    }
}