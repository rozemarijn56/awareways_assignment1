<?php

declare(strict_types=1);

$pdo = new PDO(dsn: 'sqlite:var/database.sqlite');

echo "Integrity check: ";
echo $pdo->query(query: 'PRAGMA integrity_check')->fetchColumn();
echo PHP_EOL;

echo "Tables:" . PHP_EOL;

$stmt = $pdo->query(query: "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");

foreach($stmt as $row) {
    echo "- " . $row['name'] . PHP_EOL;
}

echo "events_raw:\n";
$stmt = $pdo->query(query: "SELECT COUNT(*) AS total FROM events_raw");
print_r(value: $stmt->fetch());

echo "\nuser_activity_events:\n";
$stmt = $pdo->query(query: "SELECT COUNT(*) AS total FROM user_activity_events");
print_r(value: $stmt->fetch());

echo "\nLatest events_raw rows:\n";
$stmt = $pdo->query(query: "
    SELECT id, uuid, received_at, source, payload, is_valid, error
    FROM events_raw
    ORDER BY id DESC
    LIMIT 5
");
print_r(value: $stmt->fetchAll());

echo "\nLatest user_activity_events rows:\n";
$stmt = $pdo->query(query: "
    SELECT id, uuid, raw_id, user_id, type_activity, occurred_at
    FROM user_activity_events
    ORDER BY id DESC
    LIMIT 5
");
print_r(value: $stmt->fetchAll());