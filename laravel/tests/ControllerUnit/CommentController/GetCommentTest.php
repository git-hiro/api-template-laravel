<?php

namespace Tests\ControllerUnit\CommentController;

use App\Domains\Comment;
use App\Repositories\ICommentRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\CommentTranslator
 * @covers \App\Http\Controllers\V1\CommentController
 * @covers \App\UseCases\Comments\GetCommentCase
 *
 * @internal
 */
class GetCommentTest extends ControllerTestCase
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
        'id'   => $id,
        'name' => 'test_name_01',
      ]),
      );

    $response = $this->get("/api/v1/comments/${id}");
    $response
      ->assertStatus(200)
      ->assertJson([
        'comment' => [
          'id'   => $id,
          'name' => 'test_name_01',
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
