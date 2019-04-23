<?php

namespace Tests\GraphQLUnit\User;

use App\Domains\Article;
use App\Domains\Comment;
use App\Domains\User;
use App\Repositories\IUserRepository;
use Tests\GraphQLUnit\GraphQLTestCase;

/**
 * @covers \App\Domains\Translators\UserTranslator
 * @covers \App\Http\GraphQL\UserGQL
 * @covers \App\Http\Requests\User\GetUserRequest
 * @covers \App\UseCases\Users\GetUserCase
 *
 * @internal
 */
class GetUserTest extends GraphQLTestCase
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
        'id'       => $id,
        'name'     => 'test_name_01',
        'email'    => 'test_name_01@test.localhost',
        'articles' => [
          new Article([
            'id'      => '64574ae7-a467-445a-ad54-d1b6c4de4d9a',
            'subject' => 'test_subject_01',
            'content' => 'test_content_01',
          ]),
          new Article([
            'id'      => 'b22848a4-ba8e-464e-9373-7407c5b9ed83',
            'subject' => 'test_subject_02',
            'content' => 'test_content_02',
          ]),
        ],
        'comments' => [
          new Comment([
            'id'      => '8e83896e-e4e7-4172-9544-550b8ec6e642',
            'content' => 'test_content_01',
          ]),
          new Comment([
            'id'      => '45654e38-d95b-4d82-8656-82a4e3e09d80',
            'content' => 'test_content_02',
          ]),
        ],
      ]),
      );

    $response = $this->get("/api/v1/users/${id}");
    $response
      ->assertStatus(200)
      ->assertJson([
        'user' => [
          'id'       => $id,
          'name'     => 'test_name_01',
          'email'    => 'test_name_01@test.localhost',
          'articles' => [
            [
              'id'      => '64574ae7-a467-445a-ad54-d1b6c4de4d9a',
              'subject' => 'test_subject_01',
              'content' => 'test_content_01',
            ],
            [
              'id'      => 'b22848a4-ba8e-464e-9373-7407c5b9ed83',
              'subject' => 'test_subject_02',
              'content' => 'test_content_02',
            ],
          ],
          'comments' => [
            [
              'id'      => '8e83896e-e4e7-4172-9544-550b8ec6e642',
              'content' => 'test_content_01',
            ],
            [
              'id'      => '45654e38-d95b-4d82-8656-82a4e3e09d80',
              'content' => 'test_content_02',
            ],
          ],
        ],
      ]);

    $this->db_pgsql_spy->shouldHaveReceived('commit')->times(1);
    $this->db_pgsql_spy->shouldNotReceive('rollBack')->times(0);
  }
}
