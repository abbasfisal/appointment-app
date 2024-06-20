<?php

namespace src\utils;


class Response
{
    public static function setStatusCode(int $code): void
    {
        http_response_code($code);
    }


    public static function json($data, int $code = 200, string $message = ''): void
    {
        header('Content-Type: application/json');
        self::setStatusCode($code);

        $response = [
            'status'  => $code,
            'message' => $message,
            'data'    => $data
        ];

        echo json_encode($response);

    }
}