<?php

namespace Tests\RepositoryUnit;

use Illuminate\Support\Facades\Schema;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
abstract class RepositoryTestCase extends TestCase
{
  public function setup(): void
  {
    parent::setUp();

    $this->artisan('migrate');
  }

  protected function truncate(string $className): void
  {
    // Schema::disableForeignKeyConstraints();
    $className::query()->forceDelete();
    // Schema::enableForeignKeyConstraints();
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
}
