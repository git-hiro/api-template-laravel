<?php

namespace App\UseCases\Comments;

use App\Domains\Comment;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\ICommentRepository;

class UpdateCommentCase
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

  public function __invoke(string $id, Comment $comment, string $executor_id): Comment
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $comment, $executor_id) {
      return $this->commentRepository->update($id, $comment, $executor_id);
    });
  }
}
