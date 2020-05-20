<?php

namespace App\Exceptions;

class InvalidRouteException extends \Exception
{
    protected $message = 'Page not found.';
    protected $code = 404;
}
