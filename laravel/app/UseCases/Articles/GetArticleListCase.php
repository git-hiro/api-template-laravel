<?php

namespace App\UseCases\Articles;

use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\IArticleRepository;
use Illuminate\Support\Collection;

class GetArticleListCase
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

  public function __invoke(): Collection
  {
    return $this->connection->transaction(['pgsql'], function () {
      return $this->article_repository->getList();
    });
  }
}
