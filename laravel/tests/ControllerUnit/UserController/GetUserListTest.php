<?php

namespace Tests\ControllerUnit\UserController;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Collection;
use Tests\ControllerUnit\ControllerTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\Controllers\V1\UserController
 * @covers \App\UseCases\Users\GetUserListCase
 *
 * @internal
 */
class GetUserListTest extends ControllerTestCase
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
    $this->user_repository_mock->shouldReceive('getList')
      ->andReturn(new Collection([
        new User([
          'id'   => '77210e1b-dcdc-49d4-942b-3e4b7632ab09',
          'name' => 'test_name_01',
        ]),
        new User([
          'id'   => '28651560-a7af-4ca2-8a4d-6702b540c9600',
          'name' => 'test_name_02',
        ]),
      ]));

    $response = $this->get('/api/v1/users');
    $response->assertStatus(200);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
