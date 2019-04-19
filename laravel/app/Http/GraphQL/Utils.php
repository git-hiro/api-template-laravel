<?php

namespace App\Http\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;

class Utils
{
  public static function getRelations(ResolveInfo $info, string $root, array $keys): array
  {
    $fields = ResolveInfoUtils::getFields($info);
    \Log::debug($fields);
    $field = DictUtils::getValue($fields, $root);
    \Log::debug($field);

    return array_filter($keys, function ($value) use ($field) {
      return DictUtils::hasKeys($field, $value);
    });
  }
}

class DictUtils
{
  public static function getValue(array $array, string $keys): array
  {
    if (!$array) {
      return null;
    }

    $_array = $array;
    foreach (explode('.', $keys) as $value) {
      if (array_key_exists($value, $_array)) {
        $_array = $_array[$value];
      } else {
        return null;
      }
    }

    return $_array;
  }

  public static function hasKeys(array $array, string $keys): bool
  {
    if (!$array) {
      return false;
    }

    $_array = $array;
    foreach (explode('.', $keys) as $value) {
      if (array_key_exists($value, $_array)) {
        $_array = $_array[$value];
      } else {
        return false;
      }
    }

    return true;
  }
}

class ResolveInfoUtils
{
  public static function getFields(ResolveInfo $info): array
  {
    return self::_getFields($info->operation->toArray(true));
  }

  private static function _getFields(array $node): array
  {
    $fields = [];
    if (array_key_exists('selectionSet', $node)) {
      $sset = $node['selectionSet'];
      foreach ($sset['selections'] as $s) {
        $sname = $s['name']['value'];
        if ('Field' === $s['kind']) {
          $fields[$sname] = self::_getFields($s);
        }
      }
    }

    return $fields;
  }
}
