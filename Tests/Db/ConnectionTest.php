<?php

declare(strict_types=1);

namespace Tests\Db;

use App\Db\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

final class ConnectionTest extends TestCase
{
    public function testGetReturnsPdoInstance(): void
    {
        $pdo = Connection::get();

        $this->assertInstanceOf(expected: PDO::class, actual: $pdo);
    }

    public function testGetReturnsSameInstance(): void
    {
        $first = Connection::get();
        $second = Connection::get();

        $this->assertSame(expected: $first, actual: $second);
    }

    public function testForeignKeysPragmaIsEnabled(): void
    {
        $pdo = Connection::get();

        $result = $pdo->query(query: 'PRAGMA foreign_keys;')->fetchColumn();

        $this->assertSame(expected: 1, actual: $result);
    }
}