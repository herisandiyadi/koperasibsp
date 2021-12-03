<?php

namespace App\Exceptions;

use Exception;
use App\Models\Core\User;
use App\Helpers\ResponseHelper;

class UnhandledException extends Exception
{
    public $user;
    public $feature;
    public $previous;
    public function __construct($message = "", int $code = 0, $previous, User $user, $feature = null)
    {
        $this->user = $user;
        $this->feature = $feature;
        $this->previous = $previous;
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        \Log::alert('User: '.$this->user->email.' Unhandled Exception. Message: ' . $this->previous->getMessage() . ' File:' . $this->previous->getFile() . ' Line:' . $this->previous->getLine());
        //send email to monitoring
    }

    public function render($request)
    {
        return ResponseHelper::json('', $this->code, trans('response-message.unhandled'));
    }
}
