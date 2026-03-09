<?php

declare(strict_types=1);

namespace App\Domain;

interface  EventValidatorInterface 
{
    public function validate(array $event): array;
}