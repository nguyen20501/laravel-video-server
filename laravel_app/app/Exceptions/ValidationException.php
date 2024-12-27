<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $field;

    public function __construct($message, $field = null, $code = 400)
    {
        parent::__construct($message, $code);
        $this->field = $field;
    }

    public function getField()
    {
        return $this->field;
    }
}

