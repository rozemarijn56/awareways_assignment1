<?php

declare(strict_types=1);

namespace Tests\Support;

use App/Support/Uuid;
use PHPUnit\Framework\TestCase;

final class UuidTest extends TestCase
{
    public function testGeneratedUuidHasExpectedLength(): void
    {
        $uuid = Uuid::v4();

        $this->assertSame(36, strlen($uuid));
    }

    public function testGeneratedUuidContainsHyphensInExpectedPositions():void
    {
        $uuid = Uuid::v4();

        $this->asserSame('-', $uuid[8]);
        $this->asserSame('-', $uuid[13]);
        $this->asserSame('-', $uuid[18]);
        $this->asserSame('-', $uuid[23]);
    }
}