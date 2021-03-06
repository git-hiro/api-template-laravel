<?php

Route::group(['namespace' => 'V1', 'prefix' => 'v1'], function () {
  Route::group(['middleware' => 'auth'], function () {
    // users
    Route::resource('users', 'UserController', ['only' => ['store', 'update', 'destroy']]);

    // articles
    Route::resource('articles', 'ArticleController', ['only' => ['store', 'update', 'destroy']]);

    // comments
    Route::resource('comments', 'CommentController', ['only' => ['show', 'update', 'destroy']]);
  });

  // token
  Route::post('tokens', 'TokenController@store')->name('tokens.store');
  Route::delete('tokens', 'TokenController@destroy')->name('tokens.destroy');

  // users
  Route::resource('users', 'UserController', ['only' => ['index', 'show']]);
  Route::get('users/{user}/articles', 'UserController@indexArticles')->name('users.articles.index');
  Route::get('users/{user}/comments', 'UserController@indexComments')->name('users.comments.index');

  // articles
  Route::resource('articles', 'ArticleController', ['only' => ['index', 'show']]);
  Route::get('articles/{article}/comments', 'ArticleController@indexComments')->name('articles.comments.index');
  Route::post('articles/{article}/comments', 'ArticleController@storeComment')->name('articles.comments.store');

  // comments
  Route::resource('comments', 'CommentController', ['only' => ['show']]);
});
