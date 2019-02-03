<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\DB;

class UpdateUserCase
{
  /**
   * @var IUserRepository
   */
  private $userRepository;

  public function __construct(IUserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function __invoke(string $id, User $user, string $executorId): User
  {
    DB::beginTransaction();

    try {
      $user = $this->userRepository->update($id, $user, $executorId);
      DB::commit();
    } catch (\PDOException $ex) {
      Log::error($ex);
      DB::rollBack();
    }

    return $user;
  }
}
