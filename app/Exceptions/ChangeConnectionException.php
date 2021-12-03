<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Exception;

class ChangeConnectionException extends Exception
{
    public function report(Exception $exception)
    {
        \Log::error('Failed to set client connection. Message: ' . $exception->getMessage() . ' File:' . $exception->getFile() . ' Line:' . $exception->getLine());
        //send email to monitoring
    }

    public function render($request, Exception $exception)
    {
        return ResponseHelper::json(null, 500, 'Failed on creating connection');
    }
}
