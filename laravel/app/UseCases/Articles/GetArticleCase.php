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
  private $articleRepository;

  public function __construct(
    MultipleConnection $connection,
    IArticleRepository $articleRepository
  ) {
    $this->connection = $connection;
    $this->articleRepository = $articleRepository;
  }

  public function __invoke(string $id, array $relations = []): Article
  {
    return $this->connection->transaction(['pgsql'], function () use ($id, $relations) {
      $article = $this->articleRepository->getItem($id, $relations);
      if (!$article) {
        abort(404);
      }

      return $article;
    });
  }
}
