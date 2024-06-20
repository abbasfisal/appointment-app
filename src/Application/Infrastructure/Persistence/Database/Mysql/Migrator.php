<?php

namespace Application\Infrastructure\Persistence\Database\Mysql;

require_once __DIR__ . '/../../../../../../vendor/autoload.php';

use Configs\ConfigLoader;
use Dotenv\Dotenv;
use PDO;
use PDOException;

try {
    var_dump('hi');

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../../../../');
    $dotenv->safeLoad();

    $config = ConfigLoader::getInstance()->getAll()['database'];
    $database = $config['name'];
    $dsn = sprintf("mysql:host=%s:%s;dbname=%s", $config['host'], $config['port'], $config['name']);


    try {
        $pdo = new PDO($dsn, $config['username'], $config['password']);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }


    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database`");
    $pdo->exec("USE `$database`");

    echo "connect to database was successful \n";


    migrate($pdo);
} catch (PDOException $e) {
    echo "Failed to connect to database: " . $e->getMessage() . "\n";
    exit(1);
}

function migrate($pdo): void
{
    // Glob all migration files in the Migrations directory
    $migrationFiles = glob(__DIR__ . '/Migrations/*.php');

    foreach ($migrationFiles as $file) {
        // Include each migration file to get the SQL query
        $query = include $file;
        try {
            // Execute the SQL query
            $pdo->exec($query);
            echo basename($file) . " migration was successful \n";
        } catch (PDOException $e) {
            echo "Failed while migrating " . basename($file) . ": " . $e->getMessage() . "\n";
        }
    }
}
