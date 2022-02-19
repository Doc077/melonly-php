<?php

namespace Melonly\Http;

use Exception;
use Melonly\Support\Helpers\Str;
use Melonly\Validation\Facades\Validate;

class Request
{
    protected array $parameters = [];

    public function preferredLanguage(): string
    {
        $lang = Str::substring($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        return $lang;
    }

    public function browser(): string
    {
        return $_SERVER['HTTP_SEC_CH_UA'];
    }

    public function protocol(): string
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    public function phpVersion(): false|string
    {
        return phpversion();
    }

    public function header(string $key): string
    {
        return getallheaders()[$key];
    }

    public function headers(): false|array
    {
        return getallheaders();
    }

    public function ip(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public function file(string $name): mixed
    {
        if (!isset($_FILES[$name])) {
            throw new FileNotUploadedException("File '{$name}' has not been uploaded");
        }

        return $_FILES[$name]['tmp_name'];
    }

    public function isAjax(): bool
    {
        return $_SERVER['HTTP_X-Requested-With'] === 'XMLHttpRequest';
    }

    public function get(string $key): mixed
    {
        return $this->method() === 'GET' ? $_GET[$key] : $_POST[$key];
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function setParameters(array $values): void
    {
        $this->parameters = $values;
    }

    public function parameter(string $key): string
    {
        if (!isset($this->parameters[$key])) {
            throw new Exception("Parameter '$key' is not defined");
        }

        return $this->parameters[$key];
    }

    public function redirectData(string $name): mixed
    {
        if (Session::hasFlash($name)) {
            $value = Session::getFlash($name);

            Session::unsetFlash($name);

            return $value;
        }

        return '';
    }

    public function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function validate(array $rules): bool
    {
        return Validate::check($rules);
    }
}
