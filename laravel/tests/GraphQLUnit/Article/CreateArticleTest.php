<?php

namespace Tests\GraphQLUnit\Article;

use App\Domains\Article;
use App\Repositories\IArticleRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\GraphQL\ArticleGQL
 * @covers \App\UseCases\Articles\CreateArticleCase
 *
 * @internal
 */
class CreateArticleTest extends GraphQLTestCase
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

  public function testCreateArticle()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->article_repository_mock->shouldReceive('create')
      ->andReturn(
      new Article([
        'id'      => $id,
        'user_id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
        'subject' => 'test_subject',
        'content' => 'test_content',
      ]),
      );

    $query = $this->loadGql('/Article/graphql/CreateArticle.gql');
    $response = $this->acting()
      ->graphql($query, [
        'article' => [
          'subject' => 'test_subject',
          'content' => 'test_content',
        ],
      ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'create_article' => [
            'article' => [
              'id'      => $id,
              'user_id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
              'subject' => 'test_subject',
              'content' => 'test_content',
            ],
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
