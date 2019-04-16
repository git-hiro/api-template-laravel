<?php

Route::group(['namespace' => 'V1', 'prefix' => 'v1'], function () {
  // users
  Route::resource('users', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
  Route::get('users/{user}/articles', 'ArticleController@indexArticles')->name('users.articles.index');
  Route::get('users/{user}/comments', 'ArticleController@indexComments')->name('users.comments.index');

  // articles
  Route::resource('articles', 'ArticleController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
  Route::get('articles/{article}/comments', 'ArticleController@indexComments')->name('articles.comments.index');
  Route::post('articles/{article}/comments', 'ArticleController@storeComment')->name('articles.comments.store');

  // comments
  Route::resource('comments', 'CommentController', ['only' => ['show', 'update', 'destroy']]);
});
