<?php

namespace Melonly\Routing\Attributes;

use Attribute;
use ReflectionClass;
use ReflectionMethod;
use Melonly\Http\Method as HttpMethod;
use Melonly\Services\Container;

#[Attribute(Attribute::TARGET_METHOD)]
class Route {
    public function __construct(string $path, string $class, HttpMethod $method = HttpMethod::Get) {
        $classReflection = new ReflectionClass($class);

        $object = new $class();

        foreach ($classReflection->getMethods() as $classMethod) {
            $methodReflection = new ReflectionMethod($classMethod->class, $classMethod->name);

            $closure = $classReflection->getMethod($classMethod->name)->getClosure($object);

            Container::get(Router::class)->add($method, $path, $closure);
        }
    }
}
