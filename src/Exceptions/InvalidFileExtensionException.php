<?php


namespace Commission\Calculator\Exceptions;


class InvalidFileExtensionException extends \Exception
{
    public function __construct($message = "Invalid File Extension", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}