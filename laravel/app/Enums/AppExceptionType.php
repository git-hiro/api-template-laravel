<?php

namespace App\Enums;

use Symfony\Component\HttpFoundation\Response;

class AppExceptionType extends Enum
{
  const NOT_FOUND = [0, Response::HTTP_NOT_FOUND, 'application.not_found'];
  const CONFLICT = [1, Response::HTTP_CONFLICT, 'application.conflict'];

  public $status_code;
  public $message_key;

  protected function __construct($value)
  {
    $this->value = $value[0];
    $this->status_code = $value[1];
    $this->message_key = $value[2];
  }
}
