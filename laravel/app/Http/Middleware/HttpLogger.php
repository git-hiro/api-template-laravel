<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpLogger
{
  public function handle($request, Closure $next, $guard = null)
  {
    if ($request instanceof Request) {
      $content = null;

      switch ($request->headers->get('Content-Type')) {
  case 'application/json':
  $content = $request->json()->all();

  break;
  case 'application/x-www-form-urlencoded':
  case 'application/xml':
  $content = $request->getContent();

  break;
  default:
  break;
  }

      \Log::info('Request', [
        // 'Url'     => $request->url(),
        // 'Method'  => $request->method(),
        'query'   => $request->query(),
        'content' => $content,
        'headers' => [
          'content-type'   => $request->headers->get('Content-Type'),
          'content-length' => $request->headers->get('Content-Length'),
          'user-agent'     => $request->headers->get('User-Agent'),
        ],
      ]);
    }

    $response = $next($request);

    if ($response instanceof Response) {
      $content = null;

      switch ($response->headers->get('Content-Type')) {
  case 'application/json':
  $content = json_decode($response->content(), true);

  break;
  default:
  break;
  }

      \Log::info('Response', [
        'status'  => $response->status(),
        'content' => $content,
        'headers' => [
          'content-type'      => $response->headers->get('Content-Type'),
          'content-length'    => $response->headers->get('Content-Length'),
          'transfer-encoding' => $response->headers->get('Transfer-Encoding'),
        ],
      ]);
    }

    return $response;
  }
}
