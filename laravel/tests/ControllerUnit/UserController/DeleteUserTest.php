<?php

namespace Tests\ControllerUnit\UserController;

use App\Repositories\IUserRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\Controllers\V1\UserController
 * @covers \App\Http\Requests\User\DeleteUserRequest
 * @covers \App\UseCases\Users\DeleteUserCase
 *
 * @internal
 */
class DeleteUserTest extends ControllerTestCase
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

  public function testDeleteUser()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->user_repository_mock->shouldReceive('delete');

    $response = $this
      ->acting()
      ->delete("/api/v1/users/${id}");

    $response
      ->assertStatus(204);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
