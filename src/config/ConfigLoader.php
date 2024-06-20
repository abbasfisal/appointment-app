<?php

namespace Configs;

class ConfigLoader
{
    private static $instance;
    private $data;

    private function __construct()
    {
        $this->data = include __DIR__ . '/../config/configs.php';

    }

    public static function getInstance(): ConfigLoader
    {
        if (self::$instance == null) {
            self::$instance = new self();
            return self::$instance;
        }
        return self::$instance;
    }

    public function getAll()
    {
        return $this->data;
    }

    public function get($key)
    {
        $key = explode('.', $key);
        if (count($key) >= 2) {
            //support only with 2 indent
            $key1 = $key[0];
            $key2 = $key[1];
            return $this?->data[$key1][$key2];
        }

        return $this?->data[$key];
    }
}