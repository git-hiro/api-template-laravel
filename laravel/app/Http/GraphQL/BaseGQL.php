<?php

namespace App\Http\GraphQL;

abstract class BaseGQL
{
  public function __call($method, $args)
  {
    if (property_exists($this, $method)) {
      $property = $this->{$method};

      return $property(...$args);
    }
  }
}
