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
  public $userId;

  /**
   * @OA\Property()
   *
   * @var string
   */
  public $articleId;

  public $user;
  public $article;
}
