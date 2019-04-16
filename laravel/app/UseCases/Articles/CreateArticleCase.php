<?php

namespace App\UseCases\Articles;

use App\Domains\Article;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IArticleRepository;

class CreateArticleCase
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

  public function __invoke(Article $article, string $executor_id): Article
  {
    return $this->connection->transaction(['pgsql'], function () use ($article, $executor_id) {
      return $this->articleRepository->create($article, $executor_id);
    });
  }
}
