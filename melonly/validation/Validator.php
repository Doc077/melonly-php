<?php

namespace Melonly\Validation;

use Melonly\Http\Response;
use Melonly\Services\Container;

class Validator implements ValidatorInterface {
    protected function fulfillsRule(mixed $value, string $rule, string $field): bool {
        /**
         * Check which rule to validate.
         */
        switch (true) {
            case preg_match('/^(min):(\\d+)$/', $rule, $matches):
                if (is_int($value)) {
                    if ($value >= (int) $matches[2]) {
                        return true;
                    }

                    return false;
                } elseif (is_string($value)) {
                    if (strlen($value) >= $matches[2]) {
                        return true;
                    }

                    return false;
                }

                break;

            case preg_match('/^(max):(\\d+)$/', $rule, $matches):
                if (is_int($value)) {
                    if ($value <= (int) $matches[2]) {
                        return true;
                    }

                    return false;
                } elseif (is_string($value)) {
                    if (strlen($value) <= $matches[2]) {
                        return true;
                    }

                    return false;
                }

                break;

            case preg_match('/^(required)$/', $rule):
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    return false;
                }

                break;

            case preg_match('/^(email)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
                    return false;
                }

                break;

            case preg_match('/^(file)$/', $rule):
                if (!isset($_FILES[$field]) || !is_file($_FILES[$field])) {
                    return false;
                }

                break;

            case preg_match('/^(number)$/', $rule):
                if (!is_numeric($_POST[$field])) {
                    return false;
                }

                break;

            case preg_match('/^(alphanumeric)$/', $rule):
                if (!ctype_alnum($_POST[$field])) {
                    return false;
                }

                break;

            case preg_match('/^(int)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_INT)) {
                    return false;
                }

                break;

            case preg_match('/^(float)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_FLOAT)) {
                    return false;
                }

                break;

            case preg_match('/^(bool)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_BOOLEAN)) {
                    return false;
                }

                break;

            case preg_match('/^(domain)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_DOMAIN)) {
                    return false;
                }

                break;

            case preg_match('/^(ip)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_IP)) {
                    return false;
                }

                break;

            case preg_match('/^(url)$/', $rule):
                if (!filter_var($_POST[$field], FILTER_VALIDATE_URL)) {
                    return false;
                }

                break;

            default:
                throw new InvalidValidatorRuleException("Invalid validator rule '$rule'");
        }

        return true;
    }

    public function check(array $array): bool {
        foreach ($array as $field => $rules) {
            foreach ($rules as $rule) {
                if (!empty($_POST[$field]) && !$this->fulfillsRule($_POST[$field], $rule, $field)) {
                    /**
                     * Set HTTP 422: Unprocessable Entity status.
                     */
                    Container::get(Response::class)->status(422);

                    return false;
                }
            }
        }

        return true;
    }
}
