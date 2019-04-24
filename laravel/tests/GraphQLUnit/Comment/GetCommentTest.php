<?php

namespace Tests\GraphQLUnit\Comment;

use App\Domains\Article;
use App\Domains\Comment;
use App\Domains\User;
use App\Repositories\ICommentRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\CommentTranslator
 * @covers \App\Http\GraphQL\CommentGQL
 * @covers \App\UseCases\Comments\GetCommentCase
 *
 * @internal
 */
class GetCommentTest extends GraphQLTestCase
{
  protected $comment_repository_mock;

  public function setup(): void
  {
    parent::setUp();

    $this->comment_repository_mock = $this->addMock(ICommentRepository::class);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testGetComment()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->comment_repository_mock->shouldReceive('getItem')
      ->andReturn(
      new Comment([
        'id'         => $id,
        'user_id'    => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
        'article_id' => '505bbf26-4a3f-4504-bb00-a042baabca66',
        'content'    => 'test_content',
        'user'       => new User([
          'id'       => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
          'name'     => 'test_name_01',
          'email'    => 'test_name_01@test.localhost',
        ]),
        'article'    => new Article([
          'id'       => '505bbf26-4a3f-4504-bb00-a042baabca66',
          'user_id'  => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
          'subject'  => 'test_subject',
          'content'  => 'test_content',
        ]),
      ]),
      );

    $query = $this->loadGql('/Comment/graphql/GetComment.gql');
    $response = $this->graphql($query, [
      'id' => $id,
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'comment' => [
            'id'         => $id,
            'user_id'    => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
            'article_id' => '505bbf26-4a3f-4504-bb00-a042baabca66',
            'content'    => 'test_content',
            'user'       => [
              'id'       => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
              'name'     => 'test_name_01',
              'email'    => 'test_name_01@test.localhost',
            ],
            'article'    => [
              'id'       => '505bbf26-4a3f-4504-bb00-a042baabca66',
              'user_id'  => 'e8d239d0-0863-4a1b-bb23-dbc4bcd3abd8',
              'subject'  => 'test_subject',
              'content'  => 'test_content',
            ],
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
