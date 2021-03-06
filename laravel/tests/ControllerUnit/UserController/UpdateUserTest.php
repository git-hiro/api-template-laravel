<?php

namespace Tests\ControllerUnit\UserController;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\Controllers\V1\UserController
 * @covers \App\Http\Requests\User\UpdateUserRequest
 * @covers \App\UseCases\Users\UpdateUserCase
 *
 * @internal
 */
class UpdateUserTest extends ControllerTestCase
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

  public function testUpdateUser()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->user_repository_mock->shouldReceive('update')
      ->andReturn(
      new User([
        'id'    => $id,
        'name'  => 'test_name',
        'email' => 'test_email@test.localhost',
      ]),
      );

    $response = $this
      ->acting()
      ->putJson("/api/v1/users/${id}", [
        'user' => [
          'name'     => 'test_name',
          'email'    => 'test_email@test.localhost',
          'password' => 'test_password',
        ],
      ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'user' => [
          'id'    => $id,
          'name'  => 'test_name',
          'email' => 'test_email@test.localhost',
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
