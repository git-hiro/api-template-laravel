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
  private $comment_repository;

  public function __construct(
    MultipleConnection $connection,
    ICommentRepository $comment_repository
  ) {
    $this->connection = $connection;
    $this->comment_repository = $comment_repository;
  }

  public function __invoke(string $id, string $executor_id): void
  {
    $this->connection->transaction(['pgsql'], function () use ($id,  $executor_id) {
      $this->comment_repository->delete($id, $executor_id);
    });
  }
}
