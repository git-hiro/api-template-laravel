<?php

namespace App\UseCases\Tokens;

use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\ITokenRepository;

class DeleteTokenCase
{
  /**
   * @var MultipleConnection
   */
  private $connection;

  /**
   * @var ITokenRepository
   */
  private $token_repository;

  public function __construct(
    MultipleConnection $connection,
    ITokenRepository $token_repository
  ) {
    $this->connection = $connection;
    $this->token_repository = $token_repository;
  }

  public function __invoke(string $value): void
  {
    $this->connection->transaction(['pgsql'], function () use ($value) {
      $this->token_repository->delete($value);
    });
  }
}
