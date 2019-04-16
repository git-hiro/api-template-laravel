<?php

namespace App\Providers;

use App\Repositories as Repos;
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
    $this->app->singleton(Repos\IUserRepository::class, Repos\UserRepository::class);
    $this->app->singleton(Repos\IArticleRepository::class, Repos\ArticleRepository::class);
    $this->app->singleton(Repos\ICommentRepository::class, Repos\CommentRepository::class);
  }
}
