<?php
declare(strict_types=1);

namespace App\Http;

class Response  {
    public static function json(array $data, int $status = 200): string
    {
        http_response_code(response_code: $status);
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');
        return json_encode(value: $data);
    }
}