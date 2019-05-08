<?php

namespace Tests\GraphQLUnit\Article;

use App\Repositories\IArticleRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\GraphQL\ArticleGQL
 * @covers \App\UseCases\Articles\DeleteArticleCase
 *
 * @internal
 */
class DeleteArticleTest extends GraphQLTestCase
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

    $query = $this->loadGql('/Article/graphql/DeleteArticle.gql');
    $response = $this->acting()
      ->graphql($query, [
        'id' => $id,
      ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'delete_article' => [
            'ok' => true,
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
