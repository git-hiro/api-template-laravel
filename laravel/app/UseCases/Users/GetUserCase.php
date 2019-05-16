<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Enums\AppExceptionType;
use App\Exceptions\AppException;
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
  private $user_repository;

  public function __construct(
    MultipleConnection $connection,
    IUserRepository $user_repository
  ) {
    $this->connection = $connection;
    $this->user_repository = $user_repository;
  }

  public function __invoke(string $id, array $relations = []): User
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $relations) {
      $user = $this->user_repository->getItem($id, $relations);
      if (!$user) {
        throw new AppException(AppExceptionType::NOT_FOUND(), ['attr' => $value]);
      }

      return $user;
    });
  }
}
