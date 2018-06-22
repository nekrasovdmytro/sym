<?php

namespace App\Libs\Employee\Exception;

/**
 * Interface LoggableExceptionInterface
 * @package App\Libs\Employee\Exception
 * @author Dmytro Nekrasov <dmytro.nekrasov@internetstores.com>
 */
interface LoggableExceptionInterface extends \Throwable
{
    public function getLogMessage(): string;
}