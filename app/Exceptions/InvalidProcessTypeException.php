<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception for invalid process types
 */
class InvalidProcessTypeException extends Exception
{
    /**
     * Constructor
     *
     * @param int $processType
     */
    public function __construct(int $processType)
    {
        $message = "Invalid process type: $processType";
        parent::__construct($message);
    }
}
