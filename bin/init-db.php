<?php

declare(strict_types=1);

$dir = __DIR__ . '/../var';
$db  = $dir . '/database.sqlite';

if (!is_dir(filename: $dir)) {
    mkdir(
        directory: $dir, 
        permissions: 0700, 
        recursive: true
    );
}

if (!file_exists(filename: $db)) {
    $h = fopen(filename: $db, mode: 'c');
    if ($h === false) {
        fwrite(stream: STDERR, data: "Failed to create database file\n");
        exit(1);
    }
    fclose(stream: $h);
}

echo "DB ready at: {$db}\n";