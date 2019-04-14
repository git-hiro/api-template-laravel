<?php

namespace App\Http\Middleware;

use Closure;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\WebProcessor;

class PushLogProcessors
{
  public function handle($request, Closure $next, $guard = null)
  {
    $logger = \Log::getLogger();
    $logger->pushProcessor(new ProcessIdProcessor());
    $logger->pushProcessor(new WebProcessor());
    $logger->pushProcessor(new IntrospectionProcessor(
    Logger::DEBUG,
    [
      'Monolog\\',
      'Illuminate\\',
    ]
  ));

    return  $next($request);
  }
}
