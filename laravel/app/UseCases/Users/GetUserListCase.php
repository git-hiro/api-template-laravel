<?php

namespace App\UseCases\Users;

use App\Repositories\IUserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetUserListCase
{
  /**
   * @var IUserRepository
   */
  private $userRepository;

  public function __construct(IUserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function __invoke(): Collection
  {
    DB::beginTransaction();

    try {
      $users = $this->userRepository->getList();

      DB::commit();
    } catch (\PDOException $ex) {
      Log::error($ex);
      DB::rollBack();
    }

    return $users;
  }
}
