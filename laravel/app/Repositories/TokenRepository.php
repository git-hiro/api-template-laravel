<?php

namespace App\Repositories;

use App\Domains\Token;
use App\Domains\Translators\TokenTranslator;
use App\Repositories\Datasources\DB\TokenModel;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface ITokenRepository
{
  public function getItem(string $value, array $relations = []): ?Token;

  public function create(Token $token, string $executor_id): Token;

  public function update(string $value): Token;

  public function delete(string $value): void;
}

class TokenRepository implements ITokenRepository
{
  public function getList(array $relations = []): Collection
  {
    $query = TokenModel::query()->with($relations);
    $models = $query->get();

    return collect($models)->map(function ($model) use ($relations) {
      return TokenTranslator::ofModel($model, $relations);
    });
  }

  public function getItem(string $value, array $relations = [], bool $with_deleted = false): ?Token
  {
    $model = $this->_getModel($value, $relations, $with_deleted);

    return TokenTranslator::ofModel($model, $relations);
  }

  public function create(Token $token, string $executor_id): Token
  {
    $model = new TokenModel();
    $model->forceFill([
      'value'   => $token->value,
      'user_id' => $executor_id,
    ])->save();

    return TokenTranslator::ofModel($model);
  }

  public function update(string $value): Token
  {
    $model = $this->_getModel($value, [], false);
    if (!$model) {
      throw new NotFoundHttpException($value);
    }

    $model->update();

    return TokenTranslator::ofModel($model);
  }

  public function delete(string $value): void
  {
    $model = $this->_getModel($value, [], false);
    if ($model) {
      $model->delete();
    }
  }

  private function _getModel(string $value, array $relations, bool $with_deleted): ?TokenModel
  {
    $query = TokenModel::query()->with($relations);
    if ($with_deleted) {
      $query = $query->withTrashed();
    }

    return $query->find($value);
  }
}
