<?php

namespace Tests\GraphQLUnit;

use App\Domains\User;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
abstract class GraphQLTestCase extends TestCase
{
  protected $db_mock;
  protected $db_pgsql_spy;

  public function setup(): void
  {
    parent::setUp();

    $this->db_pgsql_spy = \Mockery::spy(Connection::class);

    $this->db_mock = $this->addMock(DatabaseManager::class);
    $this->db_mock->shouldReceive('connection')
      ->with('pgsql')->andReturn($this->db_pgsql_spy);
  }

  public function graphql(string $query, array $variables = [])
  {
    return $this->postJson('/graphql', [
      'query'     => $query,
      'variables' => $variables,
    ]);
  }

  public function loadGql(string $path)
  {
    return \File::get('tests/GraphQLUnit' . $path);
  }

  protected function addMock(string $class): MockInterface
  {
    $mock = \Mockery::mock($class);
    $this->mock = $mock;

    $this->app->forgetInstance($class);
    $this->app->singleton($class, function () use ($mock) {
      return $mock;
    });

    return $mock;
  }

  protected function addSpy(string $class): MockInterface
  {
    $spy = \Mockery::spy($class);
    $this->spy = $spy;

    $this->app->forgetInstance($class);
    $this->app->singleton($class, function () use ($spy) {
      return $spy;
    });

    return $spy;
  }

  protected function acting()
  {
    return $this->actingAs(new User([
      'id' => '90d16911-4e22-4dd2-8d8e-bc5bfb2bddd6',
    ]));
  }
}
