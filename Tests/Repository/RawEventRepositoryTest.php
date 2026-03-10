<?php 

declare(strict_types=1);

namespace Tests\Repository;

use App\Domain\RawEventRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class RawEventRepositoryTest extends TestCase
{
    public function testInsertStoresErrorMessage(): void
    {
        $pdo = new PDO('sqlite:memory:');

       $pdo->exec("
            CREATE TABLE events_raw (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                received_at TEXT,
                payload TEXT,
                is_valid INTEGER,
                error TEXT
            )
        ");

        $repo = new RawEventRepository($pdo);

        $id = $repo->insert(['foo' => 'bar'], false, 'something went wrong');

        /Repository/RawEventRepositoryTest.php

public function testInsertStoresErrorMessage(): void
{
    $pdo = new PDO('sqlite::memory:');

    $pdo->exec("
    }
}
