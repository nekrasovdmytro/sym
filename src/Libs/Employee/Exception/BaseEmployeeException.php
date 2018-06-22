<?php

namespace App\Libs\Employee\Exception;

class BaseEmployeeException extends \Exception implements LoggableExceptionInterface
{
    public function getLogMessage(): string
    {
        return static::getMessage();
    }
}