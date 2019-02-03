<?php

namespace App\Domains\Translators;

use App\Domains\Order;
use App\Repositories\Datasources\DB\OrderModel;

class OrderTranslator extends BaseTranslator
{
  public static function ofModel(?OrderModel $model, array $relations): ?Order
  {
    if (!$model) {
      return null;
    }

    $order = new Order();

    $order->id = $model->id;
    $order->userId = $model->user_id;

    $map = [
      'user' => function ($relations) use ($model) { return UserTranslator::ofModel($model->user, $relations); },
    ];
    self::setRelations($order, $map, $relations);

    return $order;
  }
}
