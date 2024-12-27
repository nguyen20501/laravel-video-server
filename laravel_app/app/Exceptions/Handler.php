<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'field' => $exception->getField(),
            ], $exception->getCode());
        }

        return parent::render($request, $exception);
    }
}
