<?php

namespace Melonly\Services;

use Melonly\Broadcasting\WebSocketConnection;
use Melonly\Database\DBConnection;
use Melonly\Encryption\Encrypter;
use Melonly\Encryption\Hasher;
use Melonly\Http\Request as HttpRequest;
use Melonly\Http\Response as HttpResponse;
use Melonly\Mailing\Mailer;
use Melonly\Routing\Router;
use Melonly\Validation\Validator;

class Container implements ContainerInterface {
    protected static array $instances = [];

    public static function get(string $key): mixed {
        if (!array_key_exists($key, self::$instances)) {
            throw new UnregisteredServiceException("Unregistered service '{$key}'");
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
            Encrypter::class,
            Hasher::class,
            Mailer::class,
            DBConnection::class,
            WebSocketConnection::class,
            Validator::class,
        ];

        foreach ($services as $service) {
            self::$instances[$service] = new $service();
        }
    }
}
