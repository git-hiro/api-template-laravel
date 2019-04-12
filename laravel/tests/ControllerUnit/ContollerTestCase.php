<?php

namespace Tests\ControllerUnit;

use Mockery\MockInterface;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ContollerTestCase extends TestCase
{
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
