<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\ActivityEventRepository;
use App\Domain\EventNormalizerInterface;
use App\Domain\EventValidatorInterface;
use App\Repository\RawEventRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class EventIngestionServiceTest extends TestCase
{
    public function testIngestReturnsInvalidWhenValidationFails(): void
    {
        $event = [
            'type' => 'invalid_event'
        ];

        $validator = $this->createMock(EventValidatorInterface::class);
        $normalizer = $this->createMock(EventNormalizerInterface::class);
        $rawRepository = $this->createMock(RawEventRepositoryInterface::class);
        $acitvityRepository = $this->createMock(ActivityEventRepository::class);

    }
}