<?php

namespace Bootstrap;

use Application\Domain\Repositories\AppointmentRepositoryInterface;
use Application\Infrastructure\Persistence\Database\Mysql\Database;
use Application\Infrastructure\Persistence\Database\Mysql\Repository\AppointmentRepository;
use Exception;
use PDO;

class App
{
    private array $bindings = [];

    public function __construct()
    {
        $this->register();
    }

    public function register(): void
    {
        $this->bindings[PDO::class] = function () {
            return Database::getInstance()->get();
        };

        $this->bindings[AppointmentRepositoryInterface::class] = function () {
            return new AppointmentRepository($this->get(PDO::class));
        };
    }

    public function bind($abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * @throws Exception
     */
    public function get($abstract)
    {
        if (isset($this->bindings[$abstract])) {

            return call_user_func($this->bindings[$abstract], $this);
        }
        throw new Exception("class {$abstract} not found.");
    }

}