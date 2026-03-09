<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Throwable;
use App\Support\uuid;

final class ActivityEventRepository 
{
    public function __construct(private PDO $pdo)
    {
    }
    public function insert(array $event, int $rawId): void
    {
        $uuid = Uuid::v4();

        $pdoStatement = $this->pdo->prepare("
          INSERT INTO user_activity_events
        (uuid, raw_id, user_id, type, training_id, progress, points, occurred_at, metadata)
        VALUES
        (:uuid, :raw_id, :user_id, :type, :training_id, :progress, :points, :occurred_at, :metadata)
        ");

        $pdoStatement->execute([
            'uuid' => $uuid,
            'raw_id' => $rawId,
            'user_id' => $event['user_id'],
            'type' => $event['type'],
            'training_id' => $event['training_id'],
            'progress' => $event['progress'],
            'points' => $event['points'],
            'occurred_at' => $event['occurred_at'],
        ]);
    }
}