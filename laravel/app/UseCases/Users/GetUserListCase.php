<?php

namespace App\UseCases\Users;

use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IUserRepository;
use Illuminate\Support\Collection;

class GetUserListCase
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

  public function __invoke(): Collection
  {
    return $this->connection->transaction(['pgsql'], function () {
      return $this->userRepository->getList();
    });
  }
}
