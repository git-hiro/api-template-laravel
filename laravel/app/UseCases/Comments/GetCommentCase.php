<?php

namespace App\UseCases\Comments;

use App\Domains\Comment;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\ICommentRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetCommentCase
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

  public function __invoke(string $id, array $relations = []): Comment
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $relations) {
      $comment = $this->comment_repository->getItem($id, $relations);
      if (!$comment) {
        throw new NotFoundHttpException($id);
      }

      return $comment;
    });
  }
}
