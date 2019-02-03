<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *   name="helloworld",
 *   description="",
 * )
 */

/**
 * @Resource("api/v1/helloworld", only={"index"})
 */
class HelloworldController extends Controller
{
  /**
   * @OA\Get(
   *   path="/api/v1/helloworld",
   *   tags={"helloworld"},
   *   @OA\Response(response="200", description="Helloworld")
   * )
   */
  public function index()
  {
    return 'Hello world';
  }
}
