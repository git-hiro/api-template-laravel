<?php

namespace Tests\ControllerUnit\ArticleController;

use App\Domains\Article;
use App\Repositories\IArticleRepository;
use Illuminate\Support\Collection;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\Controllers\V1\ArticleController
 * @covers \App\UseCases\Articles\GetArticleListCase
 *
 * @internal
 */
class GetArticleListTest extends ControllerTestCase
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

  public function testGetArticle()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->article_repository_mock->shouldReceive('getList')
      ->andReturn(new Collection([
        new Article([
          'id'      => 'cf221545-c62a-44f5-a882-3b90009663db',
          'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
          'subject' => 'test_subject',
          'content' => 'test_content',
        ]),
        new Article([
          'id'      => '3e70e30a-7549-4425-806f-40669be7f186',
          'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
          'subject' => 'test_subject',
          'content' => 'test_content',
        ]),
      ]));

    $response = $this->get('/api/v1/articles');
    $response
      ->assertStatus(200)
      ->assertJson([
        'articles' => [
          [
            'id'      => 'cf221545-c62a-44f5-a882-3b90009663db',
            'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
            'subject' => 'test_subject',
            'content' => 'test_content',
          ],
          [
            'id'      => '3e70e30a-7549-4425-806f-40669be7f186',
            'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
            'subject' => 'test_subject',
            'content' => 'test_content',
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
