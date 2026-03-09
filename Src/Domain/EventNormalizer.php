<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeInterface;
use DateTimeImmutable;

final class EventNormalizer implements EventNormalizerInterface
{
    public function normalize(array $event): array
    {
        $knownfields = [
            'event_id',
            'user_id',
            'type',
            'training_id',
            'progress',
            'points',
            'occurred_at',
            'source',
            'meta_data'
        ];

        $metadata = $event['metadata'] ?? [];

        foreach($event as $key =>$value){
            if(!in_array(needle: $key, haystack: $knownfields, strict: true)) {
                $metadata[$key] = $value;
            }
        }
        return [
            'event_id' => $event['event_id'] ?? null,
            'user_id' => (string) $event['user_id'],
            'type' => (string)$event['type'],
            'training_id' => $event['training_id'] ?? null,
            'progress' => isset($event['progress']) ? (float)$event['progress']: null,
            'points' => isset($event['points']) ?(float) $event['progress']: null,
            'occurred_at' => $this->normalizeTimestamp(event: isset($event['occurred_at']) ?? null),
            'source' => $event['source'] ?? 'platform',
            'metadata' => !empty($metadata) ? json_encode(value: $metadata, flags: JSON_THROW_ON_ERROR) : null
        ];
    }

    private function normalizeTimestamp(string $timestamp): string 
    {
        if ($timestamp === null) {
            return (new DateTimeImmutable())->format(format: DateTimeInterface::ATOM);
        }

        return (new DateTimeImmutable(datetime: $timestamp))
            ->format(format: DateTimeInterface::ATOM);
    }
}