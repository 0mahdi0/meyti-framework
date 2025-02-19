<?php

namespace App\Exceptions;

use Exception;

class MainException extends Exception
{
    public function __construct($exmsg = 'default', $val = 0, Exception $old = null)
    {
        parent::__construct($exmsg, $val, $old);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": {$this->message}\n";
    }
}