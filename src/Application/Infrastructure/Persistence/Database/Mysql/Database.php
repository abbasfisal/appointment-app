<?php

namespace Application\Infrastructure\Persistence\Database\Mysql;

use Configs\ConfigLoader;
use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = ConfigLoader::getInstance()->getAll()['database'];

        $dsn = sprintf("mysql:host=%s:%s;dbname=%s", $config['host'], $config['port'], $config['name']);

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance(): ?Database
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(): PDO
    {
        return $this->pdo;
    }

}