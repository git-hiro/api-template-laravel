<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\Finder\Finder;

/**
 * @OA\Info(title="Api Template Laravel", version="0.9")
 */
class OpenAPIController extends Controller
{
  public function index()
  {
    $openapi = \OpenApi\scan('../app');
    return $openapi->toJson();
  }
}
