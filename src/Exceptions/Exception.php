<?php

namespace App\Exceptions;

use Melonly\Exceptions\Exception as BaseException;

class Exception extends BaseException {
    protected $defaultMessage = 'Exception thrown';
}
