<?php

namespace App\Domains;

/**
 * @OA\Schema(
 *   title="User",
 *   required={"name", "email"},
 * )
 */
class User extends Entity
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

  public $orders;
}
