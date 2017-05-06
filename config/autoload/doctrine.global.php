<?php
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'mysql.hostinger.com.br',
                    'user'     => 'u720147099_twork',
                    'password' => 'ZVzqpS5m7ymj',
                    'dbname'   => 'u720147099_socne',
                ]
            ],
        ],
    ],
];
