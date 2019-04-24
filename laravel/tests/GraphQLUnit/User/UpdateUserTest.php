<?php

namespace Tests\GraphQLUnit\User;

use App\Domains\User;
use App\Repositories\IUserRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\GraphQL\UserGQL
 * @covers \App\UseCases\Users\UpdateUserCase
 *
 * @internal
 */
class UpdateUserTest extends GraphQLTestCase
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

    $query = $this->loadGql('/User/graphql/UpdateUser.gql');
    $response = $this->graphql($query, [
      'id'   => $id,
      'user' => [
        'name'     => 'test_name',
        'email'    => 'test_email@test.localhost',
        'password' => 'test_password',
      ],
    ]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'update_user' => [
            'user' => [
              'id'    => $id,
              'name'  => 'test_name',
              'email' => 'test_email@test.localhost',
            ],
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
