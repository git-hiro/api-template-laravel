<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *   name="helloworld",
 *   description="",
 * )
 */

/**
 * @Resource("api/helloworld", only={"index"})
 */
class HelloworldController extends Controller
{
  /**
   * @OA\Get(
   *   path="/api/helloworld",
   *   tags={"helloworld"},
   *   @OA\Response(response="200", description="Helloworld")
   * )
   */
  public function index()
  {
    return 'Hello world';
  }
}
