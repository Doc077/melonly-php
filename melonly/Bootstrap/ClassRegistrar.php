<?php

namespace Melonly\Bootstrap;

use ReflectionClass;
use ReflectionMethod;

class ClassRegistrar
{
    public static function registerControllers(): void
    {
        /**
         * Get all controllers and create attribute instances.
         * Here application will register HTTP routes.
         */
        foreach (getNamespaceClasses('App\Controllers') as $class) {
            $controllerClass = '\App\Controllers\\' . $class;

            $controllerReflection = new ReflectionClass($controllerClass);

            /**
             * Create instance of each controller for attribute route registering method.
             */
            new $controllerClass();

            /**
             * Get all controller public methods.
             */
            $methods = $controllerReflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $methodReflection = new ReflectionMethod($method->class, $method->name);

                foreach ($methodReflection->getAttributes() as $attribute) {
                    if ($attribute->getName() === \Melonly\Routing\Attributes\Route::class) {
                        /**
                         * Create new attribute instance & pass class name to it.
                         */
                        new \Melonly\Routing\Attributes\Route(...$attribute->getArguments(), class: $method->class);
                    }
                }
            }
        }
    }
}
