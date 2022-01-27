<?php

namespace Melonly\Container;

use Exception;
use Melonly\Broadcasting\WebSocketConnection;
use Melonly\Database\DBConnection;
use Melonly\Encryption\Encrypter;
use Melonly\Encryption\Hasher;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Mailing\Mailer;
use Melonly\Routing\Router;
use Melonly\Validation\Validator;
use ReflectionException;
use ReflectionFunction;

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
            DBConnection::class,
            Encrypter::class,
            Hasher::class,
            Mailer::class,
            Request::class,
            Response::class,
            Router::class,
            Validator::class,
            WebSocketConnection::class,
        ];

        foreach ($services as $service) {
            self::$instances[$service] = new $service();
        }
    }

    public static function resolve(callable $callable): array {
        try {
            $reflector = new ReflectionFunction($callable);
        } catch (ReflectionException) {
            throw new Exception('Cannot create instance of a service');
        }

        $services = [];

        foreach ($reflector->getParameters() as $param) {
            $class = (string) $param->getType();

            $services[] = self::has($class) ? self::get($class) : new $class();
        }

        return $services;
    }
}
