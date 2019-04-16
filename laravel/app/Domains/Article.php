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

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $subject;

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $content;

  public $user;
  public $comments;
}
