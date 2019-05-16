<?php

namespace Tests\EnumUnit;

use App\Enums\AppExceptionType;
use Tests\TestCase;

/**
 * @covers \App\Enums\AppExceptionType
 * @covers \App\Enums\Enum
 *
 * @internal
 */
class AppExceptionTypeTest extends TestCase
{
  public function testNotFound()
  {
    $type = AppExceptionType::NOT_FOUND();
    $this->assertSame($type->status_code, 404);
    $this->assertSame($type->message_key, 'application.not_found');
  }

  public function testConflict()
  {
    $type = AppExceptionType::CONFLICT();
    $this->assertSame($type->status_code, 409);
    $this->assertSame($type->message_key, 'application.conflict');
  }
}
