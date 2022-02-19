<?php

use Melonly\Config\Entry;
use Melonly\Filesystem\File;
use Melonly\Http\Session;
use Melonly\Support\Containers\Vector;
use Melonly\Support\Debug\Dumper;
use Melonly\Support\Helpers\Str;
use Melonly\Translation\Facades\Lang;
use Melonly\Views\HtmlNodeString;

if (!function_exists('__')) {
    function __(string $key): string
    {
        return trans($key);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = Entry::Unset): mixed
    {
        $parts = explode('.', $key);

        $file = __DIR__ . '/../../config/' . $parts[0] . '.php';

        if (!File::exists($file)) {
            throw new Exception("Configuration file '{$parts[0]}' does not exist");
        }

        $data = require $file;

        if (!array_key_exists($parts[1], $data)) {
            if ($default === Entry::Unset)
                throw new Exception("Configuration key '$key' is not set");
            else
                return $default;
        }

        return $data[$parts[1]];
    }
}

if (!function_exists('csrfToken')) {
    function csrfToken(): string
    {
        if (!Session::isSet('MELONLY_CSRF_TOKEN')) {
            throw new Exception('CSRF token is not set');
        }

        return Session::get('MELONLY_CSRF_TOKEN');
    }
}

if (!function_exists('directoryUp')) {
    function directoryUp(string $directory): string
    {
        return dirname($directory, 1);
    }
}

if (!function_exists('dump')) {
    function dump(...$variables): void
    {
        Dumper::dump(...$variables);
    }
}

if (!function_exists('dd')) {
    function dd(...$variables): never
    {
        dump(...$variables);

        exit();
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        if (!array_key_exists($key, $_ENV)) {
            if ($default === null) {
                throw new Exception(".env option '$key' is not set");
            } else {
                return $default;
            }
        }

        $value = $_ENV[$key];

        if ($value === false) {
            throw new Exception("Env option '$key' is invalid");
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('esc')) {
    function esc(mixed $data): void
    {
        if ($data instanceof HtmlNodeString) {
            print($data);

            return;
        }

        if ($data === null) {
            return;
        }

        print(htmlspecialchars($data));
    }
}

if (!function_exists('getNamespaceClasses')) {
    function getNamespaceClasses(string $namespace): array
    {
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

if (!function_exists('redirect')) {
    function redirect(string $url, array $data = []): never
    {
        foreach ($data as $key => $value) {
            Session::flash($key, $value);
        }

        header('Location: ' . $url);

        exit();
    }
}

if (!function_exists('redirectBack')) {
    function redirectBack(array $data = []): never
    {
        foreach ($data as $key => $value) {
            Session::flash($key, $value);
        }

        if (!isset($_SERVER['HTTP_REFERER'])) {
            throw new Exception('Cannot redirect to previous location');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit();
    }
}

if (!function_exists('str')) {
    function str(string $content): Str
    {
        return new Str($content);
    }
}

if (!function_exists('throwIf')) {
    function throwIf(bool $condition, string|object $exception, ...$params): never
    {
        if ($condition) {
            throw (is_string($exception) ? new $exception($params) : $exception($params));
        }
    }
}

if (!function_exists('trans')) {
    function trans(string $key): string
    {
        return Lang::getTranslation($key);
    }
}

if (!function_exists('vector')) {
    function vector(...$values): Vector
    {
        return new Vector(...$values);
    }
}
