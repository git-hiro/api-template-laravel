<?php

namespace App\UseCases\Users;

use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IUserRepository;

class DeleteUserCase
{
  /**
   * @var MultipleConnection
   */
  private $connection;

  /**
   * @var IUserRepository
   */
  private $user_repository;

  public function __construct(
    MultipleConnection $connection,
    IUserRepository $user_repository
  ) {
    $this->connection = $connection;
    $this->user_repository = $user_repository;
  }

  public function __invoke(string $id, string $executor_id): void
  {
    $this->connection->transaction(['pgsql'], function () use ($id,  $executor_id) {
      $this->user_repository->delete($id, $executor_id);
    });
  }
}
