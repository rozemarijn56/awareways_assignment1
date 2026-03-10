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

        $this->validateUserId(event: $event, errors: $errors);
        $this->validateType(event: $event, errors: $errors);
        $this->validateOccurredAt(event: $event, errors: $errors);
        if(!empty($event['type']) && EventTypes::isValid(type: $event['type'])) {
            $this->validateTypeSpecificRules(event: $event, errors: $errors);
        }

        return [
            'is_valid' => count(value: $errors) === 0,
            'errors' => $errors,
        ];
    }

    private function validateUserId(array $event, array &$errors): void{
        if(!isset($event['user_id']) || $event['user_id'] === ''){
            $errors[] = 'user_id is required';
        }
    }

    private function validateType(array $event, array &$errors): void {
        if(!isset($event['type']) || $event['type'] === '') {
            $errors[] = 'type is required';
        } elseif (!is_string(value: $event['type']) || !EventTypes::isvalid(type: $event['type'])){
            $errors[] = 'Type must be a valid event type';
        }
    }

    private function validateOccurredAt(array $event, array &$errors): void 
    {
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
    }

    private function validateTypeSpecificRules(array $event, array &$errors): void
    {
        switch($event['type'])
        {
            case EventTypes::POINTS_SCORED:
                $this->validatePoints(event: $event, errors: $errors);
                break;
            case EventTypes::PROGRESS_MADE:
                $this->validateProgress(event: $event, errors: $errors);
                break;

            case EventTypes::TRAINING_STARTED:
            case EventTypes::TRAINING_COMPLETED:
                $this->validateTrainingId(event: $event, errors: $errors);
                break;
        }
    }

    private function validatePoints(array $event, array &$errors): void{
                 if(isset($event['points'])) {
            if(!is_int(value: $event['points']) || $event['points'] < 0) {
                $errors[] = 'points must be a non-negative integer';
            }
        }
    }

    private function validateProgress(array $event, array &$errors): void
    {
        if(isset($event['progress'])) {
            if(!is_int(value: $event['progress']) && !is_float(value: $event['progress'])) {
                $errors[] = 'progress must be a number';
            } elseif($event['progress'] < 0 || $event['progress'] > 100) {
                $errors[] = 'progress must be between 0 and 100';
            }
        }

    }

    private function validateTrainingId(array $event, array &$errors): void
    {
        if(isset($event['training_id']) || $event['trianing_id'] === '')
            {
                $errors[] = 'training_id is required for this event type';
            }
    }
}
