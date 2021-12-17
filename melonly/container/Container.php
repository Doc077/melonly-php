<?php

namespace Melonly\Services;

use Exception;
use Melonly\Http\Request as HttpRequest;
use Melonly\Http\Response as HttpResponse;
use Melonly\Routing\Router;
use Melonly\Database\DBConnection;
use Melonly\Broadcasting\WebSocketConnection;

class Container implements ContainerInterface {
    protected static array $instances = [];

    public static function get(string $key): mixed {
        if (!array_key_exists($key, self::$instances)) {
            throw new Exception("Unregistered service '{$key}'");
        }

        return self::$instances[$key];
    }

    public static function has(string $key): bool {
        return array_key_exists($key, self::$instances);
    }

    public static function initialize(): void {
        $services = [
            HttpRequest::class,
            HttpResponse::class,
            Router::class,
            DBConnection::class,
            WebSocketConnection::class
        ];

        foreach ($services as $service) {
            self::$instances[$service] = new $service();
        }
    }
}
