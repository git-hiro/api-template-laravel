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
  private $comment_repository;

  public function __construct(
    MultipleConnection $connection,
    ICommentRepository $comment_repository
  ) {
    $this->connection = $connection;
    $this->comment_repository = $comment_repository;
  }

  public function __invoke(string $id, Comment $comment, string $executor_id): Comment
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $comment, $executor_id) {
      return $this->comment_repository->update($id, $comment, $executor_id);
    });
  }
}
