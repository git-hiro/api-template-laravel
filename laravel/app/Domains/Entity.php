<?php

namespace App\Domains;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Entity implements Arrayable, Jsonable, JsonSerializable
{
  public function __construct(array $attributes = [])
  {
    foreach ($attributes as $key => $value) {
      $this->{$key} = $value;
    }
  }

  public function toArray()
  {
    return $this->_toArray($this);
  }

  public function jsonSerialize()
  {
    return $this->toArray();
  }

  public function toJson($options = 0)
  {
    $json = json_encode($this->jsonSerialize(), $options);

    if (JSON_ERROR_NONE !== json_last_error()) {
      throw JsonEncodingException::forModel($this, json_last_error_msg());
    }

    return $json;
  }

  protected function _toArray($obj)
  {
    $array = [];
    foreach ($obj as $key => $value) {
      if (is_null($value)) {
        continue;
      }

      if ($value instanceof Arrayable) {
        $array[$key] = $value->toArray();
      } elseif (is_object($value) || is_array($value)) {
        $array[$key] = $this->_toArray($value);
      } else {
        $array[$key] = $value;
      }
    }

    return $array;
  }
}
