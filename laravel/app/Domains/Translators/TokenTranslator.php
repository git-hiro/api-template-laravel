<?php

namespace App\Domains\Translators;

use App\Domains\Token;
use App\Repositories\Datasources\DB\TokenModel;

class TokenTranslator extends BaseTranslator
{
  public static function new(array $attributes = []): Token
  {
    if (!array_key_exists('value', $attributes)) {
      // create token 32bytes
      $bytes = openssl_random_pseudo_bytes(16);
      $attributes['value'] = bin2hex($bytes);
    }

    return new Token($attributes);
  }

  public static function ofModel(?TokenModel $model, array $relations = []): ?Token
  {
    if (!$model) {
      return null;
    }

    $article = new Token([
      'value'   => $model->value,
      'user_id' => $model->user_id,
    ]);

    $map = [
      'user' => function ($relations) use ($model) {
        return UserTranslator::ofModel($model->user, $relations);
      },
    ];
    self::setRelations($article, $map, $relations);

    return $article;
  }
}
