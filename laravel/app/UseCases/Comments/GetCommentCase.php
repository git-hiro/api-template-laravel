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
  private $commentRepository;

  public function __construct(
    MultipleConnection $connection,
    ICommentRepository $commentRepository
  ) {
    $this->connection = $connection;
    $this->commentRepository = $commentRepository;
  }

  public function __invoke(string $id, array $relations = []): Comment
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $relations) {
      $comment = $this->commentRepository->getItem($id, $relations);
      if (!$comment) {
        throw new NotFoundHttpException($id);
      }

      return $comment;
    });
  }
}
