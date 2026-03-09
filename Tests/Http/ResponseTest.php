<?php

declare(strict_types=1);

namespace Tests\Http;

use App\Http\Response;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{
    protected function setUp(): void
    {
        // reset headers tussen tests
        header_remove();
    }

    public function testJsonReturnsValidJson(): void
    {
        $data = ['status' => 'ok'];

        $json = Response::json(data: $data);

        $this->assertJson(actual: $json);
        $this->assertEquals(expected: $data, actual: json_decode(json: $json, associative: true));
    }

    public function testJsonSetsStatusCode(): void
    {
        Response::json(data: ['test' => true], status: 201);

        $this->assertSame(expected: 201, actual: http_response_code());
    }

    public function testJsonContainsExpectedPayload(): void
    {
        $data = [
            'user_id' => 1,
            'type' => 'training_started'
        ];

        $json = Response::json(data: $data);

        $decoded = json_decode(json: $json, associative: true);

        $this->assertEquals(expected: 'training_started', actual: $decoded['type']);
    }
}