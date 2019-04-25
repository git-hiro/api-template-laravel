<?php

namespace App\Providers;

use App\Auth\TokenGuard;
use App\Repositories\ITokenRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  protected $policies = [
  ];

  public function boot()
  {
    $this->registerPolicies();

    $this->app['auth']->extend('token', function ($app, $name, array $config) {
      $token_repository = $this->app->make(ITokenRepository::class);

      return new TokenGuard($token_repository, $app['request']);
    });
  }
}
