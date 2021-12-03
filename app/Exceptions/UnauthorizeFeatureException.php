<?php

namespace App\Exceptions;

use Exception;
use App\Models\Core\User;
use App\Helpers\ResponseHelper;

class UnauthorizeFeatureException extends Exception
{
    public $user;
    public $feature;
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, User $user, $feature = null)
    {
        $this->user = $user;
        $this->feature = $feature;
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        \Log::alert('User: '.$this->user->email.' Message: ' . $this->message);
        //send email to monitoring
    }

    public function render($request)
    {
        return ResponseHelper::json('', 403, trans('response-message.unauthorized.feature'));
    }
}
