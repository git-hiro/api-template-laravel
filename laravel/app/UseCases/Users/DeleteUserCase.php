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
  private $userRepository;

  public function __construct(
    MultipleConnection $connection,
    IUserRepository $userRepository
  ) {
    $this->connection = $connection;
    $this->userRepository = $userRepository;
  }

  public function __invoke(string $id, string $executor_id): void
  {
    $this->connection->transaction(['pgsql'], function () use ($id,  $executor_id) {
      $this->userRepository->delete($id, $executor_id);
    });
  }
}
