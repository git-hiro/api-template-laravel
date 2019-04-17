<?php

namespace App\Enums;

use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Enum implements Jsonable, JsonSerializable
{
  public $value;
  protected static $cache = [];

  protected function __construct($value)
  {
    $this->value = $value;
  }

  final public static function __callStatic(string $key, array $args)
  {
    return static::enumerateMap()[$key];
  }

  public function __toString()
  {
    return (string)$this->value;
  }

  public function jsonSerialize()
  {
    return $this;
  }

  public function toJson($options = 0)
  {
    $json = json_encode($this->jsonSerialize(), $options);

    if (JSON_ERROR_NONE !== json_last_error()) {
      throw JsonEncodingException::forModel($this, json_last_error_msg());
    }

    return $json;
  }

  final public static function enumerates()
  {
    return array_values(static::enumerateMap());
  }

  final public static function enumerateMap()
  {
    $class = static::class;
    if (!array_key_exists($class, static::$cache)) {
      $refClass = new \ReflectionClass($class);
      $enums = [];
      foreach ($refClass->getReflectionConstants() as $const) {
        $enums[$const->getName()] = new static($const->getValue());
      }
      static::$cache[$class] = $enums;
    }

    return static::$cache[$class];
  }

  public static function of($value)
  {
    foreach (static::enumerateMap() as $enum) {
      if ($enum->value === $value) {
        return $enum;
      }
    }

    throw new \InvalidArgumentException();
  }
}
