<?php
return [
    // Configurações do banco de dados no Windows (XAMPP)
    'host' => 'localhost',
    'port' => '5432',
    'name' => 'app/database/hogwartsdb.db',
    'user' => '',
    'pass' => '',
    'type' => 'sqlite',
    'prep' => '1'
];

/**
    // Configurações do banco de dados no MySQL no Windows (XAMPP)
    'host'  =>  'localhost',      // Endereço do servidor MySQL (geralmente localhost)
    'port'  =>  '3306',           // Porta padrão do MySQL
    'name'  =>  'hogwarts_school', // Nome do banco de dados que você criou
    'user'  =>  'admin',          // Nome de usuário do MySQL
    'pass'  =>  'Admin#Senha88',  // Senha do MySQL (em branco por padrão no XAMPP)
    'type'  =>  'mysql',          // Tipo de banco de dados (mysql)
    'prep'  =>  '1',              // Preparar statements (1 para ativar)


    // Configurações com SQLite3

    'host' => 'localhost',
    'port' => '5432',
    'name' => 'app/database/hogwartsdb.db',
    'user' => '',
    'pass' => '',
    'type' => 'sqlite',
    'prep' => '1'

    ];

    // Navegue até app/database e execute o seguinte comando no terminal:
    $ sqlite3 hogwartsdb.db
    // Agora alimente o banco com instruçõoes SQL:
    $ sqlite3 hogwartsdb.db < hogwartsdb.sql

    */
