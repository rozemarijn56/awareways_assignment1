<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\EventValidatorInterface;
use App\Domain\EventNormalizerInterface;
use App\Domain\RawEventRepositoryInterface;
use App\Repository\ActivityEventRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class EventIngestionServiceTest extends TestCase
{
    public function testIngestReturnsInvalidValidationFails(): void
    {
        $event = [
            'type' => 'invalid_event'
        ];

        $validator = $this->createMock(type: EventValidatorInterface::class);
        $normalizer = $this->createMock(type: EventNormalizerInterface::class);
        $rawRepository = $this->createMock(type: RawEventRepositoryInterface::class);
        $activityRepository = $this->createMock(type: ActivityEventRepositoryInterface::class);
        
        
    }
}