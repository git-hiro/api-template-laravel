<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IUserRepository;

class CreateUserCase
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

  public function __invoke(User $user, string $executor_id): User
  {
    return $this->connection->transaction(['pgsql'], function () use ($user, $executor_id) {
      return $this->user_repository->create($user, $executor_id);
    });
  }
}
