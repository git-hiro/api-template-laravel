<?php

namespace App\UseCases\Users;

use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUserCase
{
  /**
   * @var IUserRepository
   */
  private $userRepository;

  public function __construct(IUserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function __invoke(string $id, string $executorId): void
  {
    DB::beginTransaction();

    try {
      $this->userRepository->delete($id, $executorId);
      DB::commit();
    } catch (\PDOException $ex) {
      Log::error($ex);
      DB::rollBack();
    }
  }
}
