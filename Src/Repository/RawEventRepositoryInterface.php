<?php

declare(strict_types=1);

namespace App\Repository;

interface RawEventRepositoryInterface
{
    public function insert(
        array $payload, 
        bool $isValid, 
        ?string $error = null ): int;
}