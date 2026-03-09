<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\EventTypes;
use PHPUnit\Framework\TestCase;

final class EventTypesTest extends TestCase
{
    public function testReturnAllSupportedEventTypesI(): void
    {
        $types = EventTypes::all();
        $this->assertContains(needle: EventTypes::TRAINING_STARTED, haystack: $types);
        $this->assertContains(needle: EventTypes::PROGRESS_MADE, haystack: $types);
        $this->assertContains(needle: EventTypes::POINTS_SCORED, haystack: $types);
        $this->assertContains(needle: EventTypes::TRAINING_COMPLETED, haystack: $types);
    }

    public function testIsValidReturnsTrue(): void
    {
        $this->assertTrue(condition: EventTypes::isValid(type: 'training_started'));
        $this->assertTrue(condition: EventTypes::isValid(type: 'progress_made'));
        $this->assertTrue(condition: EventTypes::isValid(type: 'points_scored'));
        $this->assertTrue(condition: EventTypes::isValid(type: 'training_completed'));
    }

    public function testIsValidReturnsfalse(): void
    {
        $this->assertFalse(condition: EventTypes::isValid(type: 'unknown_event_type'));

    }
}