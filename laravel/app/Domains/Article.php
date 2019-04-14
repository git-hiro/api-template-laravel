<?php

namespace App\Domains;

/**
 * @OA\Schema(
 *   title="Article",
 * )
 */
class Article extends Entity
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
  public $user_id;

  public $user;
  public $comments;
}
