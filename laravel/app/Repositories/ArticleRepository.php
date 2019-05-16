<?php

namespace App\Repositories;

use App\Domains\Article;
use App\Domains\Translators\ArticleTranslator;
use App\Enums\AppExceptionType;
use App\Exceptions\AppException;
use App\Repositories\Datasources\DB\ArticleModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface IArticleRepository
{
  public function getList(array $relations = []): Collection;

  public function getItem(string $id, array $relations = []): ?Article;

  public function create(Article $article, string $executor_id): Article;

  public function update(string $id, Article $article, string $executor_id): Article;

  public function delete(string $id, string $executor_id): void;
}

class ArticleRepository implements IArticleRepository
{
  public function getList(array $relations = []): Collection
  {
    $query = ArticleModel::query()->with($relations);
    $models = $query->get();

    return collect($models)->map(function ($model) use ($relations) {
      return ArticleTranslator::ofModel($model, $relations);
    });
  }

  public function getItem(string $id, array $relations = [], bool $with_deleted = false): ?Article
  {
    $model = $this->_getModel($id, $relations, $with_deleted);

    return ArticleTranslator::ofModel($model, $relations);
  }

  public function create(Article $article, string $executor_id): Article
  {
    $model = new ArticleModel();
    $model->fill($article->toArray())->forceFill([
      'id'         => $article->id,
      'user_id'    => $executor_id,
      'creator_id' => $executor_id,
      'updater_id' => $executor_id,
    ])->save();

    return ArticleTranslator::ofModel($model);
  }

  public function update(string $id, Article $article, string $executor_id): Article
  {
    $model = $this->_getModel($id, [], false);
    if (!$model) {
      throw new AppException(AppExceptionType::NOT_FOUND(), ['attr' => $id]);
    }

    $model->fill($article->toArray())->forceFill([
      'updater_id' => $executor_id,
    ])->save();

    return ArticleTranslator::ofModel($model);
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

  private function _getModel(string $id, array $relations, bool $with_deleted): ?ArticleModel
  {
    $query = ArticleModel::query()->with($relations);
    if ($with_deleted) {
      $query = $query->withTrashed();
    }

    return $query->find($id);
  }
}
