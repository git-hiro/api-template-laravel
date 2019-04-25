<?php

namespace App\Domains;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @OA\Schema(
 *   title="User",
 *   required={"name", "email"},
 * )
 */
class User extends Entity implements Authenticatable
{
  /**
   * @OA\Property()
   *
   * @var string
   */
  public $id;

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $name;

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $email;

  public $password;

  public $articles;
  public $comments;

  public function getAuthIdentifierName()
  {
    return $this->name;
  }

  public function getAuthIdentifier()
  {
    return $this->id;
  }

  public function getAuthPassword()
  {
    return $this->password;
  }

  public function getRememberToken()
  {
    throw new \BadMethodCallException();
  }

  public function setRememberToken($value)
  {
    throw new \BadMethodCallException();
  }

  public function getRememberTokenName()
  {
    throw new \BadMethodCallException();
  }
}
