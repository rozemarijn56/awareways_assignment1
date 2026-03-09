<?php

declare(strict_types=1);

namespace App\Domain;

use App\Repository\RawEventRepositoryInterface;
use DateTimeImmutable;
use Throwable;

final class EventIngestionService 
{
    public function __construct(
        private EventValidatorInterface $validator,
        private EventNormalizerInterface $event_normalizer,
        private RawEventRepositoryInterface $raw_event_repository,
        private ActivityEventRepository $activity
    ){

    }

    public function ingest(array $event): array 
    {
        $validation = $this->validator->validate(event: $event);

        $rawId = $this->raw_event_repository->insert(
            payload: $event,
            isValid: $validation['is_valid'],
            error: $validation['errors'] ? implode(', ', $validation['errors']): null
        );

        if(!$validation['is_valid'])
        {
            return [
                'status' => 'invalid',
                'errors' => $validation['errors']
            ];
        }

        $normalized = $this->event_normalizer->normalize(event: $event);

        $this->activity->insert(
            event: $normalized, rawId: $rawId
        );

        return [
            'status' => 'accepted'
        ];
    }
}