<?php

namespace App\Enums;

class ArticleType extends Enum
{
  const ZERO = [0, 'zero'];
  const ONE = [1, 'one'];
  const TWO = [2, 'two'];

  public $name;

  protected function __construct($value)
  {
    $this->value = $value[0];
    $this->name = $value[1];
  }
}
