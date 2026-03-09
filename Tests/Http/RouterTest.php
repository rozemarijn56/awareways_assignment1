<?php

declare(strict_types=1);

namespace Tests\Http;

use App\Http\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        http_response_code(200);
    }

    public function testDispatchCallsRegisteredGetRoute(): void
    {
        $router = new Router();

        $router->get('/health', function () {
            return json_encode(['status' => 'ok']);
        });

        ob_start();
        $router->dispatch('GET', '/health');
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertSame(['status' => 'ok'], json_decode($output, true));
        $this->assertSame(200, http_response_code());
    }

    public function testDispatchCallsRegisteredPostRoute(): void
    {
        $router = new Router();

        $router->post(path: '/events', hander: function (): bool|string {
            return json_encode(value: ['message' => 'created']);
        });

        ob_start();
        $router->dispatch(method: 'POST', uri: '/events');
        $output = ob_get_clean();

        $this->assertJson(actual: $output);
        $this->assertSame(expected: ['message' => 'created'], actual: json_decode(json: $output, associative: true));
        $this->assertSame(expected: 200, actual: http_response_code());
    }

    public function testDispatchReturns404ForUnknownRoute(): void
    {
        $router = new Router();

        ob_start();
        $router->dispatch(method: 'GET', uri: '/unknown');
        $output = ob_get_clean();

        $this->assertJson(actual: $output);
        $this->assertSame(expected: ['error' => 'Not found'], actual: json_decode(json: $output, associative: true));
        $this->assertSame(expected: 404, actual: http_response_code());
    }

    public function testDispatchIgnoresQueryStringWhenMatchingRoute(): void
    {
        $router = new Router();

        $router->get('/users', handler: function (): bool|string {
            return json_encode(value: ['matched' => true]);
        });

        ob_start();
        $router->dispatch(method: 'GET', uri: '/users?limit=10');
        $output = ob_get_clean();

        $this->assertJson(actual: $output);
        $this->assertSame(expected: ['matched' => true], actual: json_decode(json: $output, associative: true));
        $this->assertSame(expected: 200, actual: http_response_code());
    }
}