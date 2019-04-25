<?php

namespace App\UseCases\Articles;

use App\Domains\Article;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IArticleRepository;

class GetArticleCase
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

  public function __invoke(string $id, array $relations = []): Article
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $relations) {
      $article = $this->article_repository->getItem($id, $relations);
      if (!$article) {
        abort(404);
      }

      return $article;
    });
  }
}
