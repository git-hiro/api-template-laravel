<?php

namespace Tests\ControllerUnit\ArticleController;

use App\Repositories\IArticleRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\Controllers\V1\ArticleController
 * @covers \App\Http\Requests\Article\DeleteArticleRequest
 * @covers \App\UseCases\Articles\DeleteArticleCase
 *
 * @internal
 */
class DeleteArticleTest extends ControllerTestCase
{
  protected $article_repository_mock;

  public function setup(): void
  {
    parent::setUp();

    $this->article_repository_mock = $this->addMock(IArticleRepository::class);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testDeleteArticle()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->article_repository_mock->shouldReceive('delete');

    $response = $this->delete("/api/v1/articles/${id}");

    $response
      ->assertStatus(204);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
