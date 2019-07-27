<?php


namespace Db\Exceptions;


use Exception;
use Throwable;

class InvalidTypeException extends Exception
{
    private $type;

    public function __construct($type, Throwable $previous = null)
    {
        parent::__construct("", 0, $previous);
        $this->type = $type;
    }

    public function __toString()
    {
        return "Invalid type: " . $this->type;
    }

}