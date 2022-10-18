<?php

namespace App\Exceptions;

use App\Traits\ApiResponseHelpers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseHelpers;

    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

            if(config('app.env') == 'production') {
                if (app()->bound('sentry') && $this->shouldReport($e)) {
                    app('sentry')->captureException($e);
                }
            }
        });
    }

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }


    public function render($request, Throwable $exception)
    {

        if($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof ModelNotFoundException) {
            $modelName = class_basename($exception->getModel());
            return $this-> respondNotFound("Does not exists any {$modelName} model witch the specified identification ");
        }

        if($exception instanceof AuthorizationException) {
            return $this->respondError('Forbidden', 403);
        }

        return parent::render($request, $exception);

    }


    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->respondError($errors, 422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->respondUnAuthenticated('Unathenticated');

    }
}
