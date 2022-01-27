<?php

namespace Melonly\Bootstrap;

use App\Controllers;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class ClassRegistrar {
    public static function registerControllers(): void {
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

    public static function registerModels(): void {
        /**
         * Initialize models with column data types.
         */
        foreach (getNamespaceClasses('App\Models') as $class) {
            $modelReflection = new ReflectionClass('\App\Models\\' . $class);

            /**
             * Get all model class properties.
             */
            $properties = $modelReflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

            /**
             * Assign types to column names.
             */
            foreach ($properties as $property) {
                foreach ($property->getAttributes() as $attribute) {
                    if ($attribute->getName() === \Melonly\Database\Attributes\Column::class) {
                        /**
                         * Check whether field is nullable or not.
                         */
                        if (array_key_exists('nullable', $attribute->getArguments()) && $attribute->getArguments()['nullable']) {
                            ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = [
                                $attribute->getArguments()['type'],
                                'null'
                            ];
                        } else {
                            ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = [
                                $attribute->getArguments()['type']
                            ];
                        }
                    } elseif ($attribute->getName() === \Melonly\Database\Attributes\PrimaryKey::class) {
                        ('\App\Models\\' . $class)::$fieldTypes[$property->getName()] = 'id';
                    }
                }
            }
        }
    }
}
