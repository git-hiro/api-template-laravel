<?php

namespace Tests\GraphQLUnit\User;

use App\Repositories\IUserRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\GraphQL\UserGQL
 * @covers \App\UseCases\Users\DeleteUserCase
 *
 * @internal
 */
class DeleteUserTest extends GraphQLTestCase
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

    $query = $this->loadGql('/User/graphql/DeleteUser.gql');
    $response = $this->graphql($query, [
      'id' => $id,
    ]);
    $response
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          'delete_user' => [
            'ok' => true,
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
