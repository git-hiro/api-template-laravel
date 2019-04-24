<?php

namespace Tests\GraphQLUnit\Article;

use App\Domains\Article;
use App\Domains\Comment;
use App\Domains\User;
use App\Repositories\IArticleRepository;
use Illuminate\Support\Collection;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\ArticleTranslator
 * @covers \App\Http\GraphQL\ArticleGQL
 * @covers \App\UseCases\Articles\GetArticleListCase
 *
 * @internal
 */
class GetArticleListTest extends GraphQLTestCase
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
    $this->article_repository_mock->shouldReceive('getList')
      ->andReturn(
      new Collection([
        new Article([
          'id'      => 'cf221545-c62a-44f5-a882-3b90009663db',
          'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
          'subject' => 'test_subject',
          'content' => 'test_content',
          'user'    => new User([
            'id'    => '248e8097-f125-4644-9662-3508da676ff5',
            'name'  => 'test_name_01',
            'email' => 'test_name_01@test.localhost',
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
        ]),
        new Article([
          'id'      => '3e70e30a-7549-4425-806f-40669be7f186',
          'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
          'subject' => 'test_subject',
          'content' => 'test_content',
          'user'    => new User([
            'id'    => '248e8097-f125-4644-9662-3508da676ff5',
            'name'  => 'test_name_01',
            'email' => 'test_name_01@test.localhost',
          ]),
          'comments' => [],
        ]),
      ])
  );

    $query = $this->loadGql('/Article/graphql/GetArticleList.gql');
    $response = $this->graphql($query);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'articles' => [
            [
              'id'      => 'cf221545-c62a-44f5-a882-3b90009663db',
              'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
              'subject' => 'test_subject',
              'content' => 'test_content',
              'user'    => [
                'id'    => '248e8097-f125-4644-9662-3508da676ff5',
                'name'  => 'test_name_01',
                'email' => 'test_name_01@test.localhost',
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
            [
              'id'      => '3e70e30a-7549-4425-806f-40669be7f186',
              'user_id' => '248e8097-f125-4644-9662-3508da676ff5',
              'subject' => 'test_subject',
              'content' => 'test_content',
              'user'    => [
                'id'    => '248e8097-f125-4644-9662-3508da676ff5',
                'name'  => 'test_name_01',
                'email' => 'test_name_01@test.localhost',
              ],
              'comments' => [],
            ],
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
