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
  private $user_repository;

  public function __construct(
    MultipleConnection $connection,
    IUserRepository $user_repository
  ) {
    $this->connection = $connection;
    $this->user_repository = $user_repository;
  }

  public function __invoke(): Collection
  {
    return $this->connection->transaction(['pgsql'], function () {
      return $this->user_repository->getList();
    });
  }
}
