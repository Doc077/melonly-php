<?php

namespace Melonly\Testing;

use Melonly\Http\Http;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    protected function get(string $uri, array|string $data): mixed {
        return new Response(Http::get($uri, $data), 200);
    }

    protected function post(string $uri, array|string $data): mixed {
        return new Response(Http::post($uri, $data), 200);
    }

    protected function put(string $uri, array|string $data): mixed {
        return new Response(Http::put($uri, $data), 200);
    }

    protected function patch(string $uri, array|string $data): mixed {
        return new Response(Http::patch($uri, $data), 200);
    }

    protected function delete(string $uri, array|string $data): mixed {
        return new Response(Http::delete($uri, $data), 200);
    }
}
