<?php

namespace App\Domains;

class Token extends Entity
{
  public $value;
  public $user_id;

  public $user;
}
