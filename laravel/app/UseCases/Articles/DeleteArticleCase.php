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
  private $articleRepository;

  public function __construct(
    MultipleConnection $connection,
    IArticleRepository $articleRepository
  ) {
    $this->connection = $connection;
    $this->articleRepository = $articleRepository;
  }

  public function __invoke(string $id, string $executor_id): void
  {
    $this->connection->transaction(['pgsql'], function () use ($id,  $executor_id) {
      $this->articleRepository->delete($id, $executor_id);
    });
  }
}
