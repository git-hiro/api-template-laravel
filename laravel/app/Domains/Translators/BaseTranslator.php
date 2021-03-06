<?php

namespace App\Domains\Translators;

class BaseTranslator
{
  protected static function setRelations(object $entity, array $translatorMap, array $relations): void
  {
    $rel_map = [];
    foreach ($relations as $relation) {
      if ($relation) {
        $elements = explode('.', $relation, 2);
        $one = $elements[0];
        $others = (2 == count($elements)) ? $elements[1] : null;
        if (!array_key_exists($one, $rel_map)) {
          $rel_map[$one] = [];
        }
        if ($others) {
          $rel_map[$one][] = $others;
        }
      }
    }

    foreach ($rel_map as $key => $value) {
      if (array_key_exists($key, $translatorMap)) {
        $entity->{$key} = $translatorMap[$key]($value);
      }
    }
  }

  protected static function getProperty($name, $array, $default = null)
  {
    return array_key_exists($name, $array) ? $array[$name] : $default;
  }
}
