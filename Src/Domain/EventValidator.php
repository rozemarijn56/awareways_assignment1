<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Throwable;

final class EventValidator implements EventValidatorInterface
{
    public function validate(array $event): array
    {
        $errors = [];

        if(!isset($event['user_id']) || $event['user_id'] === ''){
            $errors[] = 'user_id is required';
        }

        if(!isset($event['type']) || $event['type'] === '') {
            $errors[] = 'type is required';
        } elseif (!is_string(value: $event['type']) || !EventTypes::isvalid(type: $event['type'])){
            $errors[] = 'Type must be a valid event type';
        }

        if(isset($event['occurred_at'])) {
            if(!is_string(value: $event['occured_at'])) {
                $errors[] = 'occurred must be a string';
            }
        } else {
            try {
                new DateTimeImmutable(datetime: $event['occured_at']);
            } catch(Throwable) {
                $errors[] = 'occurred_at must be a valid datetime'; 
            }
        }

         if(isset($event['points'])) {
            if(!is_int(value: $event['points']) || $event['points'] < 0) {
                $errors[] = 'points must be a non-negative integer';
            }
        }

        if(isset($event['progress'])) {
            if(!is_int(value: $event['progress']) && !is_float(value: $event['progress'])) {
                $errors[] = 'progress must be a number';
            } elseif($event['progress'] < 0 || $event['progress'] > 100) {
                $errors[] = 'progress must be between 0 and 100';
            }
        }

        return [
            'is_valid' => count(value: $errors) === 0,
            'errors' => $errors,
        ];
    }
}
