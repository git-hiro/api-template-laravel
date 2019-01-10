<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\Finder\Finder;

/**
 * @OA\Info(title="Api Template Laravel", version="0.9")
 */
class OpenAPIController extends Controller
{
  /**
   * @Get("openapi.json", as="openapi.index")
   */
  public function index()
  {
    $openapi = \OpenApi\scan('../app');
    return $openapi->toJson();
  }
}
