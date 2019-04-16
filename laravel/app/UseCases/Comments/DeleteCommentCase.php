<?php

namespace App\UseCases\Comments;

use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\ICommentRepository;

class DeleteCommentCase
{
  /**
   * @var MultipleConnection
   */
  private $connection;

  /**
   * @var ICommentRepository
   */
  private $commentRepository;

  public function __construct(
    MultipleConnection $connection,
    ICommentRepository $commentRepository
  ) {
    $this->connection = $connection;
    $this->commentRepository = $commentRepository;
  }

  public function __invoke(string $id, string $executor_id): void
  {
    $this->connection->transaction(['pgsql'], function () use ($id,  $executor_id) {
      $this->commentRepository->delete($id, $executor_id);
    });
  }
}
