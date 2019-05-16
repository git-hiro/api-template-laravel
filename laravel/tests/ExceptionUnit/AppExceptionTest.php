<?php

namespace Tests\EnumUnit;

use App\Enums\AppExceptionType;
use App\Exceptions\AppException;
use Tests\TestCase;

/**
 * @covers \App\Exceptions\AppException
 *
 * @internal
 */
class AppExceptionTest extends TestCase
{
  public function testNotFound()
  {
    $ex = new AppException(AppExceptionType::NOT_FOUND(), [
      'attr' => 'ATTR',
    ]);

    $this->assertSame($ex->getStatusCode(), 404);
    $this->assertSame($ex->getCategory(), 'Not Found');
    $this->assertEquals($ex->extensionsContent(), ['status_code' => 404]);
    $this->assertSame($ex->getMessage(), 'ATTR: not found');
  }

  public function testConflict()
  {
    $ex = new AppException(AppExceptionType::CONFLICT(), [
      'attr' => 'ATTR',
    ]);

    $this->assertSame($ex->getStatusCode(), 409);
    $this->assertSame($ex->getMessage(), 'ATTR: already exists');
  }
}
