<?php

namespace App\Domains\Translators;

use App\Domains\Comment;
use App\Repositories\Datasources\DB\CommentModel;
use Illuminate\Support\Str;

class CommentTranslator extends BaseTranslator
{
  public static function new(array $attributes = []): Comment
  {
    if (!array_key_exists('id', $attributes)) {
      $attributes['id'] = Str::orderedUuid()->toString();
    }

    return new Comment($attributes);
  }

  public static function ofModel(?CommentModel $model, array $relations = []): ?Comment
  {
    if (!$model) {
      return null;
    }

    $comment = new Comment([
      'id'         => $model->id,
      'user_id'    => $model->user_id,
      'article_id' => $model->article_id,
      'content'    => $model->content,
    ]);

    $map = [
      'user' => function ($relations) use ($model) {
        return UserTranslator::ofModel($model->user, $relations);
      },
      'article' => function ($relations) use ($model) {
        return ArticleTranslator::ofModel($model->article, $relations);
      },
    ];
    self::setRelations($comment, $map, $relations);

    return $comment;
  }

  public static function ofArray(array $array): ?Comment
  {
    if (!$array) {
      return null;
    }

    return self::new([
      'content' => self::getProperty('content', $array),
    ]);
  }
}
