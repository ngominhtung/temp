<?php

return [

    'default' => env('DB_CONNECTION', 'postgres'),

    'connections' => [

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789123' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789123',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789124' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789124',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789125' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789125',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789126' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789126',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789127' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789127',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789128' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789128',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql_ab3456789130' => [
            'driver' => 'pgsql',
            'host' => '10.1.7.18',
            'port' => '5432',
            'database' => 'ab3456789130',
            'username' => 'postgres',
            'password' => '12345678',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],


    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
