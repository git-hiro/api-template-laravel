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
  private $comment_repository;

  public function __construct(
    MultipleConnection $connection,
    ICommentRepository $comment_repository
  ) {
    $this->connection = $connection;
    $this->comment_repository = $comment_repository;
  }

  public function __invoke(string $article_id, Comment $comment, string $executor_id): Comment
  {
    return $this->connection->transaction(['pgsql'], function () use ($article_id, $comment, $executor_id) {
      return $this->comment_repository->create($article_id, $comment, $executor_id);
    });
  }
}
