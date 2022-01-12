<?php

namespace Melonly\Validation;

use Exception;
use Melonly\Http\Response;
use Melonly\Services\Container;

class Validator implements ValidatorInterface {
    protected function fulfillsRule(mixed $value, string $rule, string $field): bool {
        switch (true) {
            case preg_match('/^min:(\\d+)$/', $rule, $matches):
                if (is_int($value)) {
                    if ($value >= (int) $matches[1]) {
                        return true;
                    }
                } elseif (is_string($value)) {
                    if (strlen($value) >= $matches[1]) {
                        return true;
                    }
                }

                break;

            case preg_match('/^required$/', $rule):
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    return false;
                }

                break;

            default:
                throw new Exception("Invalid validator rule '$rule'");
        }
    }

    public function check(array $array): bool {
        foreach ($array as $field => $rules) {
            foreach ($rules as $rule) {
                if (!empty($_POST[$field]) && !$this->fulfillsRule($_POST[$field], $rule, $field)) {
                    Container::get(Response::class)->status(422);

                    return false;
                }
            }
        }

        return true;
    }
}
