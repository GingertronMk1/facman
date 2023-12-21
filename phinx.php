<?php

use Symfony\Component\Dotenv\Dotenv;

$dir = __DIR__;

require "{$dir}/vendor/autoload.php";

if (file_exists("{$dir}/config/bootstrap.php")) {
    echo "file exists";
    require "{$dir}/config/bootstrap.php";
} elseif (method_exists(Dotenv::class, "bootEnv")) {
    echo "loading dotenv";
    (new Dotenv())->bootEnv("{$dir}/.env");
}

$dev = [
            'adapter' => 'pgsql',
            'host' => 'database',
            'name' => $_ENV['POSTGRES_DB'],
            'user' => $_ENV['POSTGRES_USER'],
            'pass' => $_ENV['POSTGRES_PASSWORD'],
            'port' => $_ENV['POSTGRES_PORT'],
            'charset' => $_ENV['POSTGRES_CHARSET'],
];

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/../../migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/../../seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'postgresql',
            'host' => 'database',
            'name' => getenv('POSTGRES_DB'),
            'user' => getenv('POSTGRES_USER'),
            'pass' => getenv('POSTGRES_PASSWORD'),
            'port' => getenv('POSTGRES_PORT'),
            'charset' => getenv('POSTGRES_CHARSET'),
        ],
        'development' => $dev,
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
