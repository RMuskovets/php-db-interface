<?php


namespace Db\Exceptions;


use Throwable;

class InvalidActionException extends \Exception
{
    private $action;
    public function __construct($action, Throwable $previous = null)
    {
        parent::__construct("", 0, $previous);
        $this->action = $action;
    }

    public function __toString()
    {
        return "Invalid Key action: " . $this->action;
    }
}