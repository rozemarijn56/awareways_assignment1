<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Db\Connection;

// No Connection class for now: avoid path confusion.
// Use the same DB file as check-db.php:
$pdo = new PDO(dsn: 'sqlite:var/database.sqlite', username: null, password: null, options: [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);


$migrationsDirectory = __DIR__ . '/../Src/Db/Migrations';
$files = glob(pattern: $migrationsDirectory . '/*.sql');

if($files === false || count(value: $files) === 0){
    fwrite(stream: STDERR, data: "Not migration files found in {$migrationsDir}\n");
    exit(1);
}

sort(array: $files, flags: SORT_NATURAL);

try{
    $pdo->beginTransaction();

    foreach($files as $file){
        $sql = file_get_contents(filename: $file);
        if($sql === false) {
            throw new RuntimeException(message: "Failed to read migration: {$file}");
        }


        $pdo->exec(statement: $sql);
        echo "Applied: " .basename(path: $file) . PHP_EOL;
    }

    $pdo->commit();
    echo "Migrations complete. \n";
} catch(Throwable $e){
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(stream: STDERR, data: "Migration failed.\n");

    fwrite(stream: STDERR, data: $e->getMessage() . "\n");
    exit(1);
}