<?php

namespace Tests\ControllerUnit\ArticleController;

use App\Domains\Article;
use App\Repositories\IArticleRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\Controllers\V1\ArticleController
 * @covers \App\Http\Requests\Article\UpdateArticleRequest
 * @covers \App\UseCases\Articles\UpdateArticleCase
 *
 * @internal
 */
class UpdateArticleTest extends ControllerTestCase
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

  public function testUpdateArticle()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->article_repository_mock->shouldReceive('update')
      ->andReturn(
      new Article([
        'id'      => $id,
        'user_id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
        'subject' => 'test_subject',
        'content' => 'test_content',
      ]),
      );

    $response = $this->json('PUT', "/api/v1/articles/${id}", [
      'article' => [
        'subject' => 'test_subject',
        'content' => 'test_content',
      ],
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'article' => [
          'id'      => $id,
          'user_id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
          'subject' => 'test_subject',
          'content' => 'test_content',
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
