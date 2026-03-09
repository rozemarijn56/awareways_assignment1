<?php

declare(strict_types=1);

namespace App\Config;

final class Config 
{
    public static function get(string $key, ?string $default= null): ?string
    {
        if(isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if(isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return $default;
    }

    public static function require(string $key): string
    {
        $value = self::get(key: $key);

        if($value === null){
            throw new \RuntimeException(message: "Missing required config key: {$key}");
        }

        return $value;
    }
}