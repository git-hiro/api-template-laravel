<?php

namespace App\Repositories;

use App\Domains\Comment;
use App\Domains\Translators\CommentTranslator;
use App\Enums\AppExceptionType;
use App\Exceptions\AppException;
use App\Repositories\Datasources\DB\CommentModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface ICommentRepository
{
  public function getList(array $relations = []): Collection;

  public function getItem(string $id, array $relations = []): ?Comment;

  public function create(string $article_id, Comment $comment, string $executor_id): Comment;

  public function update(string $id, Comment $comment, string $executor_id): Comment;

  public function delete(string $id, string $executor_id): void;
}

class CommentRepository implements ICommentRepository
{
  public function getList(array $relations = []): Collection
  {
    $query = CommentModel::query()->with($relations);
    $models = $query->get();

    return collect($models)->map(function ($model) use ($relations) {
      return CommentTranslator::ofModel($model, $relations);
    });
  }

  public function getItem(string $id, array $relations = [], bool $with_deleted = false): ?Comment
  {
    $model = $this->_getModel($id, $relations, $with_deleted);

    return CommentTranslator::ofModel($model, $relations);
  }

  public function create(string $article_id, Comment $comment, string $executor_id): Comment
  {
    $model = new CommentModel();
    $model->fill($comment->toArray())->forceFill([
      'id'         => $comment->id,
      'user_id'    => $executor_id,
      'article_id' => $article_id,
      'creator_id' => $executor_id,
      'updater_id' => $executor_id,
    ])->save();

    return CommentTranslator::ofModel($model);
  }

  public function update(string $id, Comment $comment, string $executor_id): Comment
  {
    $model = $this->_getModel($id, [], false);
    if (!$model) {
      throw new AppException(AppExceptionType::NOT_FOUND(), ['attr' => $id]);
    }

    $model->fill($comment->toArray())->forceFill([
      'updater_id' => $executor_id,
    ])->save();

    return CommentTranslator::ofModel($model);
  }

  public function delete(string $id, string $executor_id): void
  {
    $model = $this->_getModel($id, [], false);
    if ($model) {
      $model->forceFill([
        'updater_id' => $executor_id,
        'deleted_at' => new Carbon(),
      ])->save();
    }
  }

  private function _getModel(string $id, array $relations, bool $with_deleted): ?CommentModel
  {
    $query = CommentModel::query()->with($relations);
    if ($with_deleted) {
      $query = $query->withTrashed();
    }

    return $query->find($id);
  }
}
