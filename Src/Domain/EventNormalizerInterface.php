<?php

declare(strict_types=1);

namespace App\Domain;

interface EventNormalizerInterface
{
    public function normalize(array $event): array;
}