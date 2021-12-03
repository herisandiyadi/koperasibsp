<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\ResponseHelper;

class UserNotFoundException extends Exception
{
	public function report(Exception $exception)
	{
		\Log::debug('User Not Found. Message: ' . $exception->getMessage() . ' File:' . $exception->getFile() . ' Line:' . $exception->getLine());
	}

	public function render($request, Exception $exception)
	{
		return ResponseHelper::json(null, 404, 'User not found');
	}
}
