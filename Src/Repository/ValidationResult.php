<?php

declare(strict_types=1);

namespace App\Domain;
final class ValidationResult
{
    public function __construct(
        public bool $isValid,
        public array $errors
    ) {}
}