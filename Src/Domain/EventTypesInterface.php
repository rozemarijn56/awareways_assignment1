<?php

declare(strict_types=1);

namespace App\Domain;

interface  EventTypesInterface
{
     public static function all(): array;
     
    public static function isValid(string $type): bool;

}