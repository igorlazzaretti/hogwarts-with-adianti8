<?php
return [
    'host'  =>  'localhost',      // Endereço do servidor MySQL (geralmente localhost)
    'port'  =>  '3306',           // Porta padrão do MySQL
    'name'  =>  'hogwartsdb',     // Nome do banco de dados que você criou
    'user'  =>  'admin',          // Nome de usuário do MySQL
    'pass'  =>  '',               // Senha do MySQL (em branco por padrão no XAMPP)
    'type'  =>  'mysql',          // Tipo de banco de dados (mysql)
    'prep'  =>  '1',              // Preparar statements (1 para ativar)
];

/**
    // Configurações do banco de dados no MySQL no Windows/MAC (XAMPP)
    // URL do XAMPP: http://localhost/hogwarts-school/
    // URL MySQL: http://localhost/phpmyadmin/

    'host'  =>  'localhost',      // Endereço do servidor MySQL (geralmente localhost)
    'port'  =>  '3306',           // Porta padrão do MySQL
    'name'  =>  'hogwartsdb',     // Nome do banco de dados que você criou
    'user'  =>  'admin',          // Nome de usuário do MySQL
    'pass'  =>  '',               // Senha do MySQL (em branco por padrão no XAMPP)
    'type'  =>  'mysql',          // Tipo de banco de dados (mysql)
    'prep'  =>  '1',              // Preparar statements (1 para ativar)

    GRANT ALL PRIVILEGES ON hogwartsdb.* TO 'admin'@'localhost' IDENTIFIED BY '';
    FLUSH PRIVILEGES;

    ********

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

    ********

    // Configurações do banco de dados PostgreSQL
    'host' => '10.88.0.77',     // endereço do servidor PostgreSQL
    'port' => '5432',           // porta do PostgreSQL
    'name' => 'hogwartsdb',     // nome do banco de dados
    'user' => 'postgres',       // usuário do banco de dados
    'pass' => 'postgres',       // senha do banco de dados
    'type' => 'pgsql',          // tipo do banco de dados
    'prep' => '1'               // usar prepared statements

    */
