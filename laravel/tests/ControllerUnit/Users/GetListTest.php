<?php

namespace Tests\ControllerUnit\Users;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Collection;
use Tests\ControllerUnit\ContollerTestCase;

/**
 * @internal
 * @coversNothing
 */
class GetListTest extends ContollerTestCase
{
  protected $user_repository_mock;

  public function setup(): void
  {
    parent::setUp();
    $this->user_repository_mock = $this->addRepositoryMock(IUserRepository::class);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testGetList()
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
  }
}
