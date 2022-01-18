<?php

namespace Melonly\Bootstrap;

use Melonly\Routing\Attributes\Route;
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
            $controllerReflection = new ReflectionClass('\App\Controllers\\' . $class);

            /**
             * Get all controller public methods.
             */
            $methods = $controllerReflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $methodReflection = new ReflectionMethod($method->class, $method->name);

                foreach ($methodReflection->getAttributes() as $attribute) {
                    if ($attribute->getName() === Route::class) {
                        /**
                         * Create new attribute instance & pass class name to it.
                         */
                        new Route(...$attribute->getArguments(), class: $method->class);
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
