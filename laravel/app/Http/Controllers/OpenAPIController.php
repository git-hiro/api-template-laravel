<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(title="Api Template Laravel", version="0.9")
 */

/**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     securityScheme="token",
 *     name="Authentication"
 * )
 */

/**
 * @OA\Tag(name="users", description="")
 * @OA\Tag(name="articles", description="")
 * @OA\Tag(name="comments", description="")
 */
class OpenAPIController extends Controller
{
  public function index()
  {
    $openapi = \OpenApi\scan('../app');

    return $openapi->toJson();
  }
}
