<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IUserRepository;

class UpdateUserCase
{
  /**
   * @var MultipleConnection
   */
  private $connection;

  /**
   * @var IUserRepository
   */
  private $userRepository;

  public function __construct(
    MultipleConnection $connection,
    IUserRepository $userRepository
  ) {
    $this->connection = $connection;
    $this->userRepository = $userRepository;
  }

  public function __invoke(string $id, User $user, string $executor_id): User
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $user, $executor_id) {
      return $this->userRepository->update($id, $user, $executor_id);
    });
  }
}
