<?php

namespace App\Providers;

use App\Repositories;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   */
  public function boot()
  {
  }

  /**
   * Register any application services.
   */
  public function register()
  {
    $this->app->singleton(Repositories\IUserRepository::class, Repositories\UserRepository::class);
  }
}
