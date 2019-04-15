<?php

use App\Repositories\Datasources\DB\CommentModel;
use Faker\Generator as Faker;

$factory->define(CommentModel::class, function (Faker $faker) {
  return [
    'id'         => $faker->uuid,
    'creator_id' => $faker->uuid,
    'updater_id' => $faker->uuid,
  ];
});
