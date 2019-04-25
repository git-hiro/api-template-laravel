<?php

namespace App\UseCases\Articles;

use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IArticleRepository;

class DeleteArticleCase
{
  /**
   * @var MultipleConnection
   */
  private $connection;

  /**
   * @var IArticleRepository
   */
  private $article_repository;

  public function __construct(
    MultipleConnection $connection,
    IArticleRepository $article_repository
  ) {
    $this->connection = $connection;
    $this->article_repository = $article_repository;
  }

  public function __invoke(string $id, string $executor_id): void
  {
    $this->connection->transaction(['pgsql'], function () use ($id,  $executor_id) {
      $this->article_repository->delete($id, $executor_id);
    });
  }
}
