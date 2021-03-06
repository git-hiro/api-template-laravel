<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   *
   * @param \Exception $exception
   */
  public function report(Exception $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Exception               $exception
   *
   * @return \Illuminate\Http\Response
   */
  public function render($request, Exception $exception)
  {
    if ($exception instanceof ValidationException) {
      return response()->json([
        'error' => $exception->errors(),
      ], $exception->status);
    }

    if ($this->isHttpException($exception)) {
      return response()->json([
        'errors' => $exception->getMessage(),
      ], $exception->getStatusCode());
    }

    if ($exception instanceof AuthenticationException) {
      return response()->json([
        'error' => $exception->getMessage(),
      ], Response::HTTP_UNAUTHORIZED);
    }

    \Log::notice($exception);

    return response('', Response::HTTP_INTERNAL_SERVER_ERROR);
  }
}
