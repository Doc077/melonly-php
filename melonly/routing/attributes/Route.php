<?php

namespace Melonly\Routing\Attributes;

use Attribute;
use ReflectionClass;
use Melonly\Http\Method as HttpMethod;
use Melonly\Routing\Route as RouteFacade;

#[Attribute(Attribute::TARGET_METHOD)]
class Route {
    public function __construct(string $path, string $class, HttpMethod $method = HttpMethod::Get, array $data = []) {
        $classReflection = new ReflectionClass($class);

        $object = new $class();

        foreach ($classReflection->getMethods() as $classMethod) {
            $closure = $classReflection->getMethod($classMethod->name)->getClosure($object);

            RouteFacade::add($method, $path, $closure, $data);
        }
    }
}
