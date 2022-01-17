<?php

namespace Melonly\Http;

use Exception;

class Request {
    protected null | string $parameter = null;

    public function preferredLanguage(): string {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        return $lang;
    }

    public function browser(): string {
        return $_SERVER['HTTP_SEC_CH_UA'];
    }

    public function protocol(): string {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    public function phpVersion(): false| string {
        return phpversion();
    }

    public function header(string $key): string {
        return getallheaders()[$key];
    }

    public function headers(): false | array {
        return getallheaders();
    }

    public function ip(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public function file(string $name): mixed {
        if (!isset($_FILES[$name])) {
            throw new Exception("File '{$name}' has not been uploaded");
        }

        return $_FILES[$name]['tmp_name'];
    }

    public function isAjax(): bool {
        return $_SERVER['HTTP_X-Requested-With'] === 'XMLHttpRequest';
    }

    public function getField(string $key): mixed {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET[$key];
        } else {
            return $_POST[$key];
        }
    }

    public function setParameter(mixed $value): void {
        $this->parameter = $value;
    }

    public function parameter(): string {
        return $this->parameter;
    }

    public function redirectData(string $name): mixed {
        if (isset($_SESSION['MELONLY_FLASH_' . $name])) {
            $value = $_SESSION['MELONLY_FLASH_' . $name];

            unset($_SESSION['MELONLY_FLASH_' . $name]);

            return $value;
        }

        return '';
    }

    public function uri(): string {
        return $_SERVER['REQUEST_URI'];
    }
}
