<?php

declare(strict_types=1);

namespace App\Domain;

final class EventTypes implements EventTypesInterface
{
    public const TRAINING_STARTED = 'training_started';
    public const PROGRESS_MADE = 'progress_made';
    public const POINTS_SCORED = 'points_scored';
    public const TRAINING_COMPLETED = 'training_completed';

    public static function all(): array
    {
        return [
            self::TRAINING_STARTED,
            self::PROGRESS_MADE,
            self::POINTS_SCORED,
            self::TRAINING_COMPLETED
        ];
    }

    public static function isValid(string $type): bool
    {
        return in_array(needle: $type, haystack: self::all(), strict: true);
    }
}