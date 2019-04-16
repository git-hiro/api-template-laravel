<?php

namespace Tests\ControllerUnit\CommentController;

use App\Domains\Comment;
use App\Repositories\ICommentRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\CommentTranslator
 * @covers \App\Http\Controllers\V1\CommentController
 * @covers \App\Http\Requests\Comment\StoreCommentRequest
 * @covers \App\UseCases\Comments\CreateCommentCase
 *
 * @internal
 */
class CreateArticleCommentTest extends ControllerTestCase
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

  public function testCreateArticleComment()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';
    $article_id = '995b40d6-8a25-4abb-b2a0-5820f712451b';
    $user_id = '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6';

    $this->comment_repository_mock->shouldReceive('create')
      ->andReturn(
      new Comment([
        'id'         => $id,
        'user_id'    => $user_id,
        'article_id' => $article_id,
        'content'    => 'test_content',
      ]),
      );

    $response = $this->json('POST', "/api/v1/articles/${article_id}/comments", [
      'comment' => [
        'content' => 'test_content',
      ],
    ]);

    $response
      ->assertStatus(201)
      ->assertJson([
        'comment' => [
          'id'         => $id,
          'user_id'    => $user_id,
          'article_id' => $article_id,
          'content'    => 'test_content',
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
