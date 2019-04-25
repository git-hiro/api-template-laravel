<?php

namespace App\UseCases\Articles;

use App\Domains\Article;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IArticleRepository;

class UpdateArticleCase
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

  public function __invoke(string $id, Article $article, string $executor_id): Article
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $article, $executor_id) {
      return $this->article_repository->update($id, $article, $executor_id);
    });
  }
}
