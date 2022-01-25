<?php

if (!function_exists('getNamespaceClasses')) {
    function getNamespaceClasses(string $namespace): array {
        $namespace .= '\\';

        $classList  = array_filter(get_declared_classes(), function ($item) use ($namespace) {
            return substr($item, 0, strlen($namespace)) === $namespace;
        });

        $classes = [];

        foreach ($classList as $class) {
            $parts = explode('\\', $class);

            $classes[] = end($parts);
        }

        return $classes;
    }
}
