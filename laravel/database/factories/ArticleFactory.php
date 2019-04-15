<?php

use App\Repositories\Datasources\DB\ArticleModel;
use Faker\Generator as Faker;

$factory->define(ArticleModel::class, function (Faker $faker) {
  return [
    'id'         => $faker->uuid,
    'creator_id' => $faker->uuid,
    'updater_id' => $faker->uuid,
  ];
});
