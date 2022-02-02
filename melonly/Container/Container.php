<?php

namespace Melonly\Container;

use Exception;
use ReflectionException;
use ReflectionFunction;

class Container implements ContainerInterface {
    protected static array $instances = [];

    protected static array $defaultFrameworkServices = [
        \Melonly\Authentication\Authenticator::class,
        \Melonly\Database\DBConnection::class,
        \Melonly\Encryption\Encrypter::class,
        \Melonly\Encryption\Hasher::class,
        \Melonly\Logging\Logger::class,
        \Melonly\Mailing\Mailer::class,
        \Melonly\Http\Request::class,
        \Melonly\Http\Response::class,
        \Melonly\Routing\Router::class,
        \Melonly\Validation\Validator::class,
        \Melonly\Translation\Translator::class,
        \Melonly\Broadcasting\WebSocketConnection::class,
    ];

    public static function initialize(): void {
        foreach (self::$defaultFrameworkServices as $service) {
            self::$instances[$service] = new $service();
        }

        foreach (config('services.bindings') as $userDefinedService) {
            self::$instances[$userDefinedService] = new $userDefinedService();
        }
    }

    public static function get(string $key): mixed {
        if (!array_key_exists($key, self::$instances)) {
            throw new UnregisteredServiceException("Unregistered service '{$key}'");
        }

        return self::$instances[$key];
    }

    public static function set(string $class): mixed {
        self::$instances[$class] = new $class();

        return self::$instances[$class];
    }

    public static function has(string $key): bool {
        return array_key_exists($key, self::$instances);
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
