<?php

use App\Repositories\Datasources\DB\TokenModel;
use Faker\Generator as Faker;

$factory->define(TokenModel::class, function (Faker $faker) {
  $bytes = openssl_random_pseudo_bytes(16);
  $value = bin2hex($bytes);

  return [
    'value'   => $value,
    'user_id' => $faker->uuid,
  ];
});
