<?php

namespace Tests\ControllerUnit\UserController;

use App\Domains\Comment;
use App\Domains\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Collection;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\Controllers\V1\UserController
 * @covers \App\UseCases\Users\GetUserCase
 *
 * @internal
 */
class GetUserCommentListTest extends ControllerTestCase
{
  protected $user_repository_mock;

  public function setup(): void
  {
    parent::setUp();

    $this->user_repository_mock = $this->addMock(IUserRepository::class);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testGetUserList()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->user_repository_mock->shouldReceive('getItem')
      ->andReturn(
      new User([
        'id'       => $id,
        'name'     => 'test_name_01',
        'articles' => new Collection([
          new Comment([
            'id'      => 'bd2101b2-6231-448c-bae0-caab9ff68563',
            'subject' => 'test_subject',
            'content' => 'test_content',
          ]),
        ]),
      ]),
      );

    $response = $this->get("/api/v1/users/{$id}");
    $response->assertStatus(200);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
