<?php

namespace App\Repositories;

use App\Domains\Translators\UserTranslator;
use App\Domains\User;
use App\Repositories\Datasources\DB\UserModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface IUserRepository
{
  public function getList(array $relations = []): Collection;

  public function getItem(string $id, array $relations = []): ?User;

  public function getItemByUnique(string $mail, array $relations = []): ?User;

  public function create(User $user, string $executorId): User;

  public function update(string $id, User $user, string $executorId): User;

  public function delete(string $id, string $executorId): void;
}

class UserRepository implements IUserRepository
{
  public function getList(array $relations = []): Collection
  {
    $query = UserModel::query()->with($relations);
    $models = $query->get();

    return collect($models)->map(function ($model) use ($relations) {return UserTranslator::ofModel($model, $relations); });
  }

  public function getItem(string $id, array $relations = [], bool $withDeleted = false): ?User
  {
    $model = $this->_getModel($id, $relations, $withDeleted);

    return UserTranslator::ofModel($model, $relations);
  }

  public function getItemByUnique(string $email, array $relations = [], bool $withDeleted = false): ?User
  {
    $model = $this->_getModelByUnique($email, $relations, $withDeleted);

    return UserTranslator::ofModel($model, $relations);
  }

  public function create(User $user, string $executorId): User
  {
    $model = $this->_getModelByUnique($user->email, [], true);
    if ($model) {
      if ($model->deleted_at) {
        $model->forceDelete();
      } else {
        throw new InvalidArgumentException();
      }
    }

    $model = new UserModel();
    $model->fill($user->toArray())->forceFill([
      'creator_id' => $executorId,
      'updater_id' => $executorId,
    ])->save();

    return UserTranslator::ofModel($model);
  }

  public function update(string $id, User $user, string $executorId): User
  {
    if ($user->email) {
      $model = $this->_getModelByUnique($user->email, [], true);
      if ($model && $model->id !== $id) {
        if ($model->deleted_at) {
          $model->forceDelete();
        } else {
          throw new InvalidArgumentException();
        }
      }
    }

    $model = $this->_getModel($id, [], false);
    if (!$model) {
      throw new InvalidArgumentException();
    }

    $model->fill($user->toArray())->forceFill([
      'updater_id' => $executorId,
    ])->save();

    return UserTranslator::ofModel($model);
  }

  public function delete(string $id, string $executorId): void
  {
    $model = $this->_getModel($id, [], false);
    if ($model) {
      $model->forceFill([
        'updater_id' => $executorId,
        'deleted_at' => new Carbon(),
      ])->save();
    }
  }

  private function _getModel(string $id, array $relations, bool $withDeleted): ?UserModel
  {
    $query = UserModel::query()->with($relations);
    if ($withDeleted) {
      $query = $query->withTrashed();
    }

    return $query->find($id);
  }

  private function _getModelByUnique(string $email, array $relations, bool $withDeleted): ?UserModel
  {
    $query = UserModel::query()->where('email', $email)->with($relations);
    if ($withDeleted) {
      $query = $query->withTrashed();
    }

    return $query->first();
  }
}
