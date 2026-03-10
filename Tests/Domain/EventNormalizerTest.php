<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\EventNormalizer;
use PHPUnit\Framework\TestCase;

class EventNormalizerTest extends TestCase
{
    private EventNormalizer $eventNormalizer;

    protected function setUp():void
    {
        $this->eventNormalizer = new EventNormalizer();
    }

    public function testNormalizeEvent()
    {
        $event = [
            'user_id' => 123,
            'type' => 'training_started',
            'training_id' => 'abc'
        ];

        $result = $this->eventNormalizer->normalize(event: $event);

        $this->assertSame('123', $result['user_id']);
        $this->assertSame('training_started', $result['type']);
        $this->assertSame('abc', $result['training_id']);
        $this->assertArrayHasKey('occcurred_at', $result);
    }

    public function testMetaDataExtraction(): void
    {
        $event = [
            'user_id' => 1,
            'type' => 'points_scored',
            'points' => 10,
            'source' => 'mobile'
        ];

        $result = $this->eventNormalizer->normalize($event);

        $this->assertSame(['source' => 'mobile'], $result['metadata']);
    }

    public function testMissingTimestampGetsGenerated(): void
    {
        $event = [
            'user_id' => 1,
            'type' => 'training_started'
        ];

        $result = $this->eventNormalizer->normalize($event);

        $this->assertNotEmpty($result['occurred_at']);
    }
}