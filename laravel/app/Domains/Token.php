<?php

namespace App\Domains;

/**
 * @OA\Schema(
 *   title="Token",
 * )
 */
class Token extends Entity
{
  /**
   * @OA\Property()
   *
   * @var string
   */
  public $value;

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $user_id;

  public $user;
}
