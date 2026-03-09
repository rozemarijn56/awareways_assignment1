<?php

declare(strict_types=1);

namespace App\Db;

use PDO;
use PDOException;

final class Connection 
{
    private static ?PDO $pdo = null;
    public static function get(): PDO
    {
        if(self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $databasePath = self::resolveDatabasePath();
        echo 'Using database: ' . $databasePath . PHP_EOL;
        self::ensureDatabaseDirectoryIsSafe(databasePath: $databasePath);

        $dsn = 'sqlite:' . $databasePath;

        try{
            $pdo = new PDO(dsn: $dsn, username: null, password: null, options: [
                // Security & correctness defaults
                PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES      => false,
                //Don't stringify numbers; keeps types consistent
                PDO::ATTR_STRINGIFY_FETCHES     => false
            ]);
        } catch(PDOException $e) {
            // Don't leak filesystem paths / DSNs to the client
             throw new \RuntimeException(message: 'Database connection failed: ' . $e->getMessage());
        }

        // SQLite hardening + sane defaults
        // Note some PRAGMAs are trade-offs: we choose safe/robust for this assignment
        $pdo->exec(statement: 'PRAGMA foreign_keys = ON;');
        $pdo->exec(statement: 'PRAGMA journal_mode = WAL;');     // better concurrency, safer writes
        $pdo->exec(statement: 'PRAGMA synchronous = NORMAL;');   // balanced; FULL is safer but slower
        $pdo->exec(statement: 'PRAGMA busy_timeout = 5000;');    // prevent “database is locked” errors
        $pdo->exec(statement: 'PRAGMA temp_store = MEMORY;');

        self::$pdo = $pdo;
        return self::$pdo;
    }
    private static function resolveDatabasePath(): string 
    {
        // Keep DB outside public/ for safety
       
        $path = $_ENV['DB_PATH'] ?? 'var/database.sqlite';
        $root = dirname(path: __DIR__, levels: 2);
        $databasePath = $root . DIRECTORY_SEPARATOR . $path;

        return $databasePath;
    }

    private static function ensureDatabaseDirectoryIsSafe(string $databasePath): void
    {
        $directory = dirname(path: $databasePath);

        if(!is_dir(filename: $directory)) {
            if(!mkdir(
                directory: $directory, 
                permissions: 0700, 
                recursive: true) && 
                !is_dir(filename: $directory)) {
                throw new \RuntimeException(message: 'Failed to created database connection.');
            }
        };

        @chmod(filename: $directory, permissions: 0700);

        // Ensure file exists
        if (!file_exists(filename: $databasePath)) {
            $handle = @fopen(filename: $databasePath, mode: 'c');
            if ($handle === false) {
                throw new \RuntimeException(message: 'Failed to create database file.');
            }
            fclose(stream: $handle);
        }

    }
}