<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Db\Connection;
use Ramsey\Uuid\Uuid;

$pdo = Connection::get();

echo "Seeding events...\n";

$users = ['1', '2', '3'];
$trainings = ['security-basics', 'phishing-awareness', 'gdpr-training'];

$types = [
    'training_started',
    'progress_made',
    'points_scored',
    'training_completed'
];

$stmtRaw = $pdo->prepare(query: '
    INSERT INTO events_raw (uuid, received_at, source, payload, is_valid)
    VALUES (:uuid, :received_at, :source, :payload, :is_valid)
');

$stmtActivity = $pdo->prepare(query: "
    INSERT INTO user_activity_events (
        uuid,
        raw_id,
        user_id,
        type_activity,
        training_id,
        progress,
        points,
        occurred_at,
        metadata
    )
    VALUES (
        :uuid,
        :raw_id,
        :user_id,
        :type_activity,
        :training_id,
        :progress,
        :points,
        :occurred_at,
        :metadata
    )
");

for ($i = 0; $i < 20; $i++) {

    $user = $users[array_rand(array: $users)];
    $training = $trainings[array_rand(array: $trainings)];
    $type_activity = $types[array_rand(array: $types)];

    $progress = null;
    $points = null;

    if ($type_activity === 'progress_made') {
        $progress = random_int(min: 5, max: 100);
    }

    if ($type_activity === 'points_scored') {
        $points = random_int(min: 5, max: 50);
    }

    $occurredAt = (new DateTimeImmutable())
        ->modify(modifier: "-" . random_int(min: 0, max: 5) . " days")
        ->format(format: DateTimeInterface::ATOM);

    $eventPayload = [
        'user_id' => $user,
        'type_activity' => $type_activity,
        'training_id' => $training,
        'progress' => $progress,
        'points' => $points,
        'occurred_at' => $occurredAt
    ];

    $uuid = Uuid::uuid4()->toString();

    $stmtRaw->execute(params: [
        'uuid' => $uuid,
        'received_at' => (new DateTimeImmutable())->format(format: DateTimeInterface::ATOM),
        'source' => 'test',
        'payload' => json_encode(value: $eventPayload),
        'is_valid' => 1
    ]);

    $rawId = $pdo->lastInsertId();

    $stmtActivity->execute(params: [
        'uuid' => Uuid::uuid4()->toString(),
        'raw_id' => $rawId,
        'user_id' => $user,
        'type_activity' => $type_activity,
        'training_id' => $training,
        'progress' => $progress,
        'points' => $points,
        'occurred_at' => $occurredAt,
        'metadata' => json_encode(value: [
            'source' => 'seed'
        ])
    ]);
}

echo "Seed complete.\n"; 