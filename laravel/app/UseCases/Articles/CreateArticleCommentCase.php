<?php

namespace App\UseCases\Articles;

use App\Domains\Comment;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\ICommentRepository;

class CreateArticleCommentCase
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

  public function __invoke(string $article_id, Comment $comment, string $executor_id): Comment
  {
    return $this->connection->transaction(['pgsql'], function () use ($article_id, $comment, $executor_id) {
      return $this->commentRepository->create($article_id, $comment, $executor_id);
    });
  }
}
