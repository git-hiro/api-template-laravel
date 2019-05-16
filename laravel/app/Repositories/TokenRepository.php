<?php

namespace App\Repositories;

use App\Domains\Token;
use App\Domains\Translators\TokenTranslator;
use App\Enums\AppExceptionType;
use App\Exceptions\AppException;
use App\Repositories\Datasources\DB\TokenModel;

interface ITokenRepository
{
  public function getItem(string $value, array $relations = []): ?Token;

  public function create(Token $token, string $executor_id): Token;

  public function update(string $value): Token;

  public function delete(string $value): void;
}

class TokenRepository implements ITokenRepository
{
  public function getItem(string $value, array $relations = []): ?Token
  {
    $model = $this->_getModel($value, $relations);

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
    $model = $this->_getModel($value, []);
    if (!$model) {
      throw new AppException(AppExceptionType::NOT_FOUND(), ['attr' => $value]);
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

  private function _getModel(string $value, array $relations): ?TokenModel
  {
    $query = TokenModel::query()->with($relations);

    return $query->find($value);
  }
}
