<?php

namespace App\Domains\Translators;

use App\Domains\Article;
use App\Repositories\Datasources\DB\ArticleModel;
use Illuminate\Support\Str;

class ArticleTranslator extends BaseTranslator
{
  public static function new(array $attributes = []): Article
  {
    if (!array_key_exists('id', $attributes)) {
      $attributes['id'] = Str::orderedUuid()->toString();
    }

    return new Article($attributes);
  }

  public static function ofModel(?ArticleModel $model, array $relations = []): ?Article
  {
    if (!$model) {
      return null;
    }

    $article = new Article([
      'id'      => $model->id,
      'user_id' => $model->user_id,
      'subject' => $model->subject,
      'content' => $model->content,
    ]);

    $map = [
      'user' => function ($relations) use ($model) {
        return UserTranslator::ofModel($model->user, $relations);
      },
      'comments' => function ($relations) use ($model) {
        return collect($model->comments)->map(function ($comment) use ($relations) {
          return CommentTranslator::ofModel($comment, $relations);
        });
      },
    ];
    self::setRelations($article, $map, $relations);

    return $article;
  }

  public static function ofArray(array $array): ?Article
  {
    if (!$array) {
      return null;
    }

    return self::new([
      'subject' => self::getProperty('subject', $array),
      'content' => self::getProperty('content', $array),
    ]);
  }
}
