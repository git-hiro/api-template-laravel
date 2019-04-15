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

  public function create(User $user, string $executor_id): User;

  public function update(string $id, User $user, string $executor_id): User;

  public function delete(string $id, string $executor_id): void;
}

class UserRepository implements IUserRepository
{
  public function getList(array $relations = []): Collection
  {
    $query = UserModel::query()->with($relations);
    $models = $query->get();

    return collect($models)->map(function ($model) use ($relations) {
      return UserTranslator::ofModel($model, $relations);
    });
  }

  public function getItem(string $id, array $relations = [], bool $with_deleted = false): ?User
  {
    $model = $this->_getModel($id, $relations, $with_deleted);

    return UserTranslator::ofModel($model, $relations);
  }

  public function getItemByUnique(string $email, array $relations = [], bool $with_deleted = false): ?User
  {
    $model = $this->_getModelByUnique($email, $relations, $with_deleted);

    return UserTranslator::ofModel($model, $relations);
  }

  public function create(User $user, string $executor_id): User
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
      'id'         => $user->id,
      'creator_id' => $executor_id,
      'updater_id' => $executor_id,
    ])->save();

    return UserTranslator::ofModel($model);
  }

  public function update(string $id, User $user, string $executor_id): User
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
      'updater_id' => $executor_id,
    ])->save();

    return UserTranslator::ofModel($model);
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

  private function _getModel(string $id, array $relations, bool $with_deleted): ?UserModel
  {
    $query = UserModel::query()->with($relations);
    if ($with_deleted) {
      $query = $query->withTrashed();
    }

    return $query->find($id);
  }

  private function _getModelByUnique(string $email, array $relations, bool $with_deleted): ?UserModel
  {
    $query = UserModel::query()->where('email', $email)->with($relations);
    if ($with_deleted) {
      $query = $query->withTrashed();
    }

    return $query->first();
  }
}
