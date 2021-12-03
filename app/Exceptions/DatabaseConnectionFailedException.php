<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use App\Models\Core\User;
use Exception;
use Throwable;

class DatabaseConnectionFailedException extends Exception
{
    public $user;
    public $feature;
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, User $user, $feature = null)
    {
        $this->user = $user;
        $this->feature = $feature;
        parent::__construct($message, $code, $previous);
    }

    public function report(Exception $exception)
    {
        \Log::critical('User: '.$this->user->email.'Failed to connect database or database not found. Message: ' . $exception->getMessage() . ' File:' . $exception->getFile() . ' Line:' . $exception->getLine());
        //send email to monitoring
    }

    public function render($request, Exception $exception)
    {
        return ResponseHelper::json(null, 500, 'Failed on creating connection');
    }
}
