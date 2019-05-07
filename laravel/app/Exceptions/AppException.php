<?php

namespace App\Exceptions;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AppException extends Exception implements RendersErrorsExtensions, HttpExceptionInterface
{
  protected $status_code;

  public function __construct(
    int $status_code,
    string $message
  ) {
    parent::__construct($message);

    $this->status_code = $status_code;
  }

  //region RendersErrorsExtensions

  public function isClientSafe(): bool
  {
    return true;
  }

  public function getCategory(): string
  {
    return Response::$statusTexts[$this->status_code];
  }

  public function extensionsContent(): array
  {
    return [
      'status_code' => $this->status_code,
    ];
  }

  //endregion RendersErrorsExtensions

  //region HttpExceptionInterface

  public function getStatusCode(): int
  {
    return $this->status_code;
  }

  public function getHeaders(): array
  {
    return [];
  }

  //endregion HttpExceptionInterface
}
