<?php

declare(strict_types=1);

namespace App\Support;

final class uuid
{
    public static function v4(): string {
        $data = random_bytes(length: 16);

        $data[6] = chr(codepoint: ord(character: $data[6]) & 0x0f | 0x40);
        $data[8] = chr(codepoint: ord(character: $data[8]) & 0x0f | 0x80);

        return vsprintf(format: '%s%s-%s-%s-%s-%s%s%s', values: str_split(string: bin2hex(string: $data), length: 4));
    }
}