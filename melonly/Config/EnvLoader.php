<?php

namespace Melonly\Config;

use Exception;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;

class EnvLoader
{
    protected array $variables = [];

    protected function parse(): void
    {
        $lines = File::read(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (trim($line)[0] === '#' || !Str::contains('=', $line)) {
                continue;
            }

            $variable = explode('=', $line)[0];
            $value = explode('=', $line)[1];

            /**
             * Handle variable interpolation.
             * 
             * Syntax: VAR_NAME=${OTHER_VAR_NAME}
             */
            if (preg_match('/\$\{(.*?)\}/', $value, $matches)) {
                isset($this->variables[$matches[1]])
                    ? $this->variables[$variable] = $this->variables[$matches[1]]
                    : throw new Exception(".env variable '{$matches[1]} is not set'");

                return;
            }

            $this->variables[$variable] = $value;
        }
    }

    public function load(): void
    {
        $this->parse();

        foreach ($this->variables as $variable => $value) {
            $_ENV[$variable] = $value;
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->variables);
    }

    public function get(string $key): mixed
    {
        return $this->variables[$key];
    }
}
