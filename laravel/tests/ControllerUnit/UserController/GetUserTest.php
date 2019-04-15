<?php

namespace Tests\ControllerUnit\UserController;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @internal
 * @coversNothing
 */
class GetUserTest extends ControllerTestCase
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

  public function testGetUser()
  {
    $id = '60df8adf-8f54-4923-9720-e224a57882a9';

    $this->user_repository_mock->shouldReceive('getItem')
      ->andReturn(
      new User([
        'id'   => $id,
        'name' => 'test_name_01',
      ]),
      );

    $response = $this->get("/api/v1/users/${id}");
    $response
      ->assertStatus(200)
      ->assertJson([
        'user' => [
          'id'   => $id,
          'name' => 'test_name_01',
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
