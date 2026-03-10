<?php

declare(strict_types=1);

namespace App\Repository;

interface ActivityEventRepositoryInterface
{
        public function insert(array $event, int $rawId): void;
}