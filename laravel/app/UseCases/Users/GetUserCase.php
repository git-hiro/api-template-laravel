<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IUserRepository;

class GetUserCase
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

  public function __invoke(string $id, array $relations = []): User
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $relations) {
      $user = $this->userRepository->getItem($id, $relations);
      if (!$user) {
        abort(404);
      }

      return $user;
    });
  }
}
