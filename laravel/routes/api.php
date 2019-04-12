<?php

Route::group(['namespace' => 'V1', 'prefix' => 'v1'], function () {
  Route::resource('users', 'UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
});
