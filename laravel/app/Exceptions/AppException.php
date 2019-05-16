<?php

namespace App\Exceptions;

use App\Enums\AppExceptionType;
use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class AppException extends Exception implements RendersErrorsExtensions, HttpExceptionInterface
{
  protected $type;

  public function __construct(
    AppExceptionType $type,
    array $attributes = [],
    string $locale = null
  ) {
    parent::__construct(__($type->message_key, $attributes, $locale));
    $this->type = $type;
  }

  //region RendersErrorsExtensions

  public function isClientSafe(): bool
  {
    return true;
  }

  public function getCategory(): string
  {
    return Response::$statusTexts[$this->getStatusCode()];
  }

  public function extensionsContent(): array
  {
    return [
      'status_code' => $this->getStatusCode(),
    ];
  }

  //endregion RendersErrorsExtensions

  //region HttpExceptionInterface

  public function getStatusCode(): int
  {
    return $this->type->status_code;
  }

  public function getHeaders(): array
  {
    return [];
  }

  //endregion HttpExceptionInterface
}
