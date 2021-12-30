<?php

namespace Melonly\Routing\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionMethod;
use Melonly\Http\Method as HttpMethod;
use Melonly\Services\Container;
use Melonly\Routing\Router;

#[Attribute(Attribute::TARGET_METHOD)]
class Route {
    public function __construct(string $path, string $class, HttpMethod $method = HttpMethod::Get, array $data = []) {
        $classReflection = new ReflectionClass($class);

        $object = new $class();

        foreach ($classReflection->getMethods() as $classMethod) {
            $methodReflection = new ReflectionMethod($classMethod->class, $classMethod->name);

            $closure = $classReflection->getMethod($classMethod->name)->getClosure($object);
            // $closure = function () use ($class, $classMethod) {
            //     $class = '\\' . $class;
            //     $controller = new $class();

            //     echo $controller->{$classMethod->name}();
            //     echo 5555;
            // };

            Container::get(Router::class)->add($method, $path, $closure, $data);
        }
    }
}
