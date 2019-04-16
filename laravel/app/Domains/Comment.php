<?php

namespace App\Domains;

/**
 * @OA\Schema(
 *   title="Comment",
 * )
 */
class Comment extends Entity
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
  public $article_id;

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $content;

  public $user;
  public $article;
}
