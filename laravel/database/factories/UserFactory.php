<?php

use App\Repositories\Datasources\DB\UserModel;
use Faker\Generator as Faker;

$factory->define(UserModel::class, function (Faker $faker) {
  static $password = 'password';

  return [
    'id'         => $faker->uuid,
    'name'       => $faker->name,
    'email'      => $faker->unique()->safeEmail,
    'password'   => $password ?: $password = bcrypt('secret'),
    'creator_id' => $faker->uuid,
    'updater_id' => $faker->uuid,
  ];
});
