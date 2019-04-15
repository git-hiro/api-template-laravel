<?php

namespace App\Domains\Translators;

use App\Domains\User;
use App\Repositories\Datasources\DB\UserModel;
use Illuminate\Support\Str;

class UserTranslator extends BaseTranslator
{
  public static function new(array $attributes = []): User
  {
    if (!array_key_exists('id', $attributes)) {
      $attributes['id'] = Str::orderedUuid()->toString();
    }

    return new User($attributes);
  }

  public static function ofModel(?UserModel $model, array $relations = []): ?User
  {
    if (!$model) {
      return null;
    }

    $user = new User([
      'id'       => $model->id,
      'name'     => $model->name,
      'email'    => $model->email,
      // 'password' => $model->password,
    ]);

    $map = [
      'comments' => function ($relations) use ($model) {
        return collect($model->comments)->map(function ($comment) use ($relations) {
          return CommentTranslator::ofModel($comment, $relations);
        });
      },
      'articles' => function ($relations) use ($model) {
        return collect($model->articles)->map(function ($article) use ($relations) {
          return ArticleTranslator::ofModel($article, $relations);
        });
      },
    ];
    self::setRelations($user, $map, $relations);

    return $user;
  }

  public static function ofArray(array $array): ?User
  {
    if (!$array) {
      return null;
    }

    return new User([
      'id'       => self::getProperty('id', $array),
      'name'     => self::getProperty('name', $array),
      'email'    => self::getProperty('email', $array),
      'password' => self::getProperty('password', $array),
    ]);
  }
}
