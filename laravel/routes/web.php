<?php

switch (env('APP_ENV', 'development')) {
  case 'local':
  case 'development':
  Route::get('openapi.json', 'OpenAPIController@index')->name('openapi.index');

  break;
  default:
  break;
}
