<?php

namespace Tests\GraphQLUnit\CommentGraphQL;

use App\Repositories\ICommentRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\CommentTranslator
 * @covers \App\Http\GraphQL\CommentGQL
 * @covers \App\UseCases\Comments\DeleteCommentCase
 *
 * @internal
 */
class DeleteCommentTest extends GraphQLTestCase
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

  public function testDeleteComment()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->comment_repository_mock->shouldReceive('delete');

    $query = $this->loadGql('/Comment/graphql/DeleteComment.gql');
    $response = $this->graphql($query, [
      'id' => $id,
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'delete_comment' => [
            'ok' => true,
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
