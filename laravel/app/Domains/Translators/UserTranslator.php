<?php

namespace App\Domains\Translators;

use App\Domains\User;
use App\Repositories\Datasources\DB\UserModel;

class UserTranslator extends BaseTranslator
{
  public static function ofModel(?UserModel $model, array $relations = []): ?User
  {
    if (!$model) {
      return null;
    }

    $user = new User();

    $user->id = $model->id;
    $user->name = $model->name;
    $user->email = $model->email;
    // $user->password = $model->password;

    $map = [
      'orders' => function ($relations) use ($model) { return collect($model->orders)->map(function ($order) use ($relations) { return OrderTranslator::ofModel($order, $relations); }); },
    ];
    self::setRelations($user, $map, $relations);

    return $user;
  }

  public static function ofArray(array $array): ?User
  {
    if (!$array) {
      return null;
    }

    $user = new User();

    $user->id = self::getProperty('id', $array);
    $user->name = self::getProperty('name', $array);
    $user->email = self::getProperty('email', $array);
    $user->password = self::getProperty('password', $array);

    return $user;
  }
}
