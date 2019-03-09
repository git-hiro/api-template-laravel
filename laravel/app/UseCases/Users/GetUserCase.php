<?php

namespace App\UseCases\Users;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetUserCase
{
  /**
   * @var IUserRepository
   */
  private $userRepository;

  public function __construct(IUserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function __invoke(string $id): User
  {
    DB::beginTransaction();

    try {
      $user = $this->userRepository->getItem($id);
      if (!$user) {
        abort(404);
      }

      DB::commit();
    } catch (\PDOException $ex) {
      Log::error($ex);
      DB::rollBack();
    }

    return $user;
  }
}
