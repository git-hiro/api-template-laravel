<?php

namespace Tests\GraphQLUnit\Article;

use App\Domains\Article;
use App\Domains\Comment;
use App\Domains\User;
use App\Repositories\IArticleRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\GraphQL\ArticleGQL
 * @covers \App\UseCases\Articles\GetArticleCase
 *
 * @internal
 */
class GetArticleTest extends GraphQLTestCase
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

    $this->article_repository_mock->shouldReceive('getItem')
      ->andReturn(
      new Article([
        'id'      => $id,
        'user_id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
        'subject' => 'test_subject',
        'content' => 'test_content',
        'user'    => new User([
          'id'       => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
          'name'     => 'test_name_01',
          'email'    => 'test_name_01@test.localhost',
        ]),
        'comments' => [
          new Comment([
            'id'      => '8e83896e-e4e7-4172-9544-550b8ec6e642',
            'content' => 'test_content_01',
          ]),
          new Comment([
            'id'      => '45654e38-d95b-4d82-8656-82a4e3e09d80',
            'content' => 'test_content_02',
          ]),
        ],
      ])
  );

    $query = $this->loadGql('/Article/graphql/GetArticle.gql');
    $response = $this->graphql($query, [
      'id' => $id,
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'article' => [
            'id'      => $id,
            'user_id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
            'subject' => 'test_subject',
            'content' => 'test_content',
            'user'    => [
              'id'       => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
              'name'     => 'test_name_01',
              'email'    => 'test_name_01@test.localhost',
            ],
            'comments' => [
              [
                'id'      => '8e83896e-e4e7-4172-9544-550b8ec6e642',
                'content' => 'test_content_01',
              ],
              [
                'id'      => '45654e38-d95b-4d82-8656-82a4e3e09d80',
                'content' => 'test_content_02',
              ],
            ],
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
