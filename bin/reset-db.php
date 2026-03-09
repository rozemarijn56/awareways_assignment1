<?php

declare(strict_types=1);

$dbPath = __DIR__ . '/../var/database.sqlite';

if (file_exists(filename: $dbPath)) {
    unlink(filename: $dbPath);
    echo "Deleted database file." . PHP_EOL;
}

passthru(command: 'php bin/init-db.php');
passthru(command: 'php bin/migrate.php');
passthru(command: 'php bin/seed.php');

echo "Database reset complete." . PHP_EOL;