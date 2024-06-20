<?php

namespace src\utils;

use Exception;

class ErrorHandler
{
    public static function handle(Exception $e): void
    {
        $code = $e->getCode() ?: 500;
        $message = $e->getMessage() ?: 'Internal Server Error';

        Response::json([], $code, $message);
    }
}
