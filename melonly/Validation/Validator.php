<?php

namespace Melonly\Validation;

use Melonly\Container\Container;
use Melonly\Http\Response;
use Melonly\Http\Status;
use Melonly\Validation\Rules;

class Validator implements ValidatorInterface
{
    protected array $rulePatterns = [
        '/^(accepted)$/' => Rules\AcceptedRule::class,
        '/^(alphanumeric)$/' => Rules\AlphanumericRule::class,
        '/^(bool)$/' => Rules\BoolRule::class,
        '/^(domain)$/' => Rules\DomainRule::class,
        '/^(email)$/' => Rules\EmailRule::class,
        '/^(file)$/' => Rules\FileRule::class,
        '/^(float)$/' => Rules\FloatRule::class,
        '/^(image)$/' => Rules\ImageRule::class,
        '/^(int)$/' => Rules\IntRule::class,
        '/^(ip)$/' => Rules\IpRule::class,
        '/^(max):(\\d+)$/' => Rules\MaxRule::class,
        '/^(min):(\\d+)$/' => Rules\MinRule::class,
        '/^(number)$/' => Rules\NumberRule::class,
        '/^(regex):(\\d+)$/' => Rules\RegexRule::class,
        '/^(required)$/' => Rules\RequiredRule::class,
        '/^(string)$/' => Rules\StringRule::class,
        '/^(unique):(\\d+)$/' => Rules\UniqueRule::class,
        '/^(url)$/' => Rules\UrlRule::class,
    ];

    protected function isValidForRule(mixed $value, string $rule, string $field): bool
    {
        $matchesOneRule = false;

        foreach ($this->rulePatterns as $pattern => $ruleClass) {
            if (preg_match($pattern, $rule, $matches)) {
                $matchesOneRule = true;

                return (new $ruleClass())->check($field, $value, isset($matches[2]) ? $matches[2] : null);
            }
        }

        if (!$matchesOneRule) {
            throw new InvalidValidatorRuleException("Invalid validator rule '$rule'");
        }

        return true;
    }

    public function check(array $array): bool
    {
        foreach ($array as $field => $rules) {
            foreach ($rules as $rule) {
                if (!empty($_POST[$field]) && !$this->isValidForRule($_POST[$field], $rule, $field)) {
                    Container::get(Response::class)->status(Status::UnprocessableEntity);
                    Container::get(Response::class)->redirectBack();

                    return false;
                }
            }
        }

        return true;
    }
}
