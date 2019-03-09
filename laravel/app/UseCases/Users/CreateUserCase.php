<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateUserCase
{
  /**
   * @var IUserRepository
   */
  private $userRepository;

  public function __construct(IUserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function __invoke(User $user, string $executorId): User
  {
    DB::beginTransaction();

    try {
      $user = $this->userRepository->create($user, $executorId);
      DB::commit();
    } catch (\PDOException $ex) {
      Log::error($ex);
      DB::rollBack();
    }

    return $user;
  }
}
