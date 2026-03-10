<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\EventTypes;
use App\Domain\EventValidator;
use PHPUnit\Framework\TestCase;

final class EventValidatorTest extends TestCase
{
    private EventValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new EventValidator();
    }

    public function testValidateReturnsValid(): void
    {
        $event = [
            'user_id' => '123',
            'type' => EventTypes::TRAINING_STARTED,
            'occurred_at' => '2026-03-06T12:00:00+00:00'
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertTrue(condition: $result['is_valid']);
        $this->assertSame(expected: [], actual: $result['errors']);
    }

    public function testValidateFalseWhenUserIdIsMissing(): void
    {
        $event = [ 
            'type' => EventTypes::TRAINING_STARTED
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertFalse(condition: $result['is_valid']);
        $this->assertContains(needle: 'user_id is required', haystack: $result['errors']);
    }

    public function testValidateTypeIsInvalid(): void
    {
        $event = [
            'user_id' => '123',
            'type' => 'not_a_real_type'
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertFalse(condition: $result['is_valid']);
        $this->assertContains(needle: 'user_id is required', haystack: $result['errors']);
    }

    public function testValidateFailsWhenOccurredIsInvalid(): void
    {
        $event = [
            'user_id' => '123',
            'type' => EventTypes::TRAINING_STARTED,
            'occurred_at' => 'not-a-date'
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertFalse(condition: $result['is_valid']);
        $this->assertContains(needle: 'occurred must be a valid datetime', haystack: $result['errors']);

    }

    public function testValidatePointsIsNegative(): void
    {
        $event = [
            'user_id' => '123',
            'type' => EventTypes::POINTS_SCORED,
            'points' => -27,
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertFalse(condition: $result['is_valid']);
        $this->assertContains(needle: 'points must be a non negative integer', haystack: $result['errors']);
    }

    public function testValidateProgressIsOutOfRange(): void
    {
        $event = [
            'user_id' => '123',
            'type' => EventTypes::PROGRESS_MADE,
            'progress' => 120,
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertFalse(condition: $result['is_valid']);
        $this->assertContains(needle: 'progress must be between 0 and 100', haystack: $result['errors']);
    }

    public function testEventRequiresPoints(): void
    {
        $event = [
            'user_id' => '123',
            'type' => EventTypes::POINTS_SCORED
        ];

        $result = $this->validator->validate(event: $event);

        $this->assertFalse(condition: $result['is_valid']);
        $this->assertContains(needle: 'points is required for points_scored event', haystack: $result['errors']);
    }
}