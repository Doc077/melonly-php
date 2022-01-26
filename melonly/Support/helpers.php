<?php

use Melonly\Http\Session;
use Melonly\Support\Containers\Vector;
use Melonly\Translation\Lang;
use Melonly\Views\HtmlNodeString;

if (!function_exists('__')) {
    function __(string $key): string {
        return trans($key);
    }
}

if (!function_exists('csrfToken')) {
    function csrfToken(): string {
        if (!Session::isSet('MELONLY_CSRF_TOKEN')) {
            throw new Exception('CSRF token is not set');
        }

        return Session::get('MELONLY_CSRF_TOKEN');
    }
}

if (!function_exists('dump')) {
    function dump(...$variables): void {
        print('<div style="padding: 14px 16px; background: #2a2b32; color: #ddd; font-family: Cascadia Mono, Consolas, monospace; font-size: 14px; border-radius: 10px; line-height: 1.6;">');
        print('<div style="color: #b8bcc9; margin-bottom: 8px;">Dumped variables (' . count($variables) . '):</div>');

        foreach ($variables as $variable) {
            $type = gettype($variable);

            $value = $variable;

            switch ($type) {
                case 'array':
                    $value = '[' . implode(', ', $value) . ']' . '<span style="color: #e491fd;"> / ' . count($value) . ' item(s)</span>';

                    break;

                case 'string':
                    $value = "'{$value}'" . '<span style="color: #e491fd;"> / ' . strlen($value) . ' character(s)</span>';

                    break;

                case 'boolean':
                    $value = $value ? 'true' : 'false';

                    break;

                case 'object':
                    $type = get_class($variable);

                    $value = 'new ' . get_class($variable) . '()';

                    break;

                case 'NULL':
                    $value = 'null';
                    $type = 'null';

                    break;
            }

            $arrayTypes = [];

            if (is_array($variable)) {
                $types = [];

                foreach ($variable as $element) {
                    if (gettype($element) === 'object') {
                        $types[] = get_class($element);

                        continue;
                    }

                    $types[] = strtolower(gettype($element));
                }

                $types = array_unique($types);

                $arrayTypes = implode(', ', $types);
            }

            print('<div><span style="color: #e491fd;">' . $type . '</span>' . (is_array($variable) ? "&lt;<span style=\"color: #8ec6ff;\">{$arrayTypes}</span>&gt;" : '') . ': <span style="color: #a1cf7f;">' . (is_array($variable) ? '[' : $value) . '</span></div>');

            if (is_array($variable)) {
                foreach ($variable as $element) {
                    print('<div style="width: 10px; margin-left: 20px;">');

                    switch (gettype($element)) {
                        case 'array':
                            print('array');
            
                            break;
            
                        case 'string':
                            print($element);
            
                            break;
            
                        case 'boolean':
                            print($value ? 'true' : 'false');
            
                            break;
            
                        case 'object':
                            $type = get_class($element);
            
                            print('new ' . get_class($element) . '()');
            
                            break;
            
                        case 'NULL':
                            print('null');
            
                            break;
                    }

                    print('</div>');
                }
            }
        }

        print('</div>');
    }
}

if (!function_exists('dd')) {
    function dd(...$variables): never {
        dump(...$variables);

        exit();
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        $value = $_ENV[$key];

        if (!isset($value)) {
            if ($default === null) {
                throw new Exception("Env option '$key' is not set");
            } else {
                return $default;
            }
        }

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

if (!function_exists('printData')) {
    function printData(mixed $data): void {
        if ($data instanceof HtmlNodeString) {
            print($data);

            return;
        }

        print(htmlspecialchars($data));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, array $data = []): never {
        foreach ($data as $key => $value) {
            Session::set('MELONLY_FLASH_' . $key, $value);
        }

        header('Location: ' . $url);

        exit();
    }
}

if (!function_exists('redirectBack')) {
    function redirectBack(array $data = []): never {
        foreach ($data as $key => $value) {
            Session::set('MELONLY_FLASH_' . $key, $value);
        }

        if (!isset($_SERVER['HTTP_REFERER'])) {
            throw new Exception('Cannot redirect to previous location');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit();
    }
}

if (!function_exists('throwIf')) {
    function throwIf(bool $condition, string | object $exception, ...$params): never {
        if ($condition) {
            throw (is_string($exception) ? new $exception($params) : $exception($params));
        }
    }
}

if (!function_exists('trans')) {
    function trans(string $key): string {
        $parts = explode('.', $key);

        $file = __DIR__ . '/../../frontend/lang/' . Lang::getCurrent() . '/' . $parts[0] . '.json';

        if (!file_exists($file)) {
            throw new Exception("Translation file '{$parts[0]}' does not exist");
        }

        $json = json_decode(file_get_contents($file), true);

        if (!array_key_exists($parts[1], $json)) {
            return $key;
        }

        return $json[$parts[1]];
    }
}

if (!function_exists('vector')) {
    function vector(...$values): Vector {
        return new Vector(...$values);
    }
}
