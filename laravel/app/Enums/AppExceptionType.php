<?php

namespace App\Enums;

use Symfony\Component\HttpFoundation\Response;

class AppExceptionType extends Enum
{
  const INTERNAL_SERVER_ERROR = [Response::HTTP_INTERNAL_SERVER_ERROR, 'application.internal_server_error'];

  const UNAUTHORIZED = [Response::HTTP_UNAUTHORIZED, 'application.unauthorized'];
  const NOT_FOUND = [Response::HTTP_NOT_FOUND, 'application.not_found'];
  const CONFLICT = [Response::HTTP_CONFLICT, 'application.conflict'];

  public $status_code;
  public $message_key;

  protected function __construct($value)
  {
    $this->value = $value;
    $this->status_code = $value[0];
    $this->message_key = $value[1];
  }
}
