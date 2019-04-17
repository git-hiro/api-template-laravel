<?php

namespace Tests\EnumUnit;

use App\Enums\ArticleType;
use Tests\TestCase;

/**
 * @covers \App\Enums\ArticleType
 *
 * @internal
 */
class ArticleTypeTest extends TestCase
{
  public function testEnumerats()
  {
    $types = ArticleType::enumerates();

    \Log::debug($types);
    \Log::debug(json_encode($types));
    $this->assertCount(3, $types);
    $this->assertJsonStringEqualsJsonString(json_encode([[
      'name'  => 'zero',
      'value' => 0,
    ], [
      'name'  => 'one',
      'value' => 1,
    ], [
      'name'  => 'two',
      'value' => 2,
    ]]), json_encode($types));
  }

  public function testToJson()
  {
    $type = ArticleType::ZERO();

    \Log::debug($type);
    \Log::debug($type->toJson());
    $this->assertJsonStringEqualsJsonString(json_encode([
      'name'  => 'zero',
      'value' => 0,
    ]), $type->toJson());
  }

  public function testEqual()
  {
    $type = ArticleType::of(1);

    \Log::debug($type);
    $this->assertSame(ArticleType::ONE(), $type);
    $this->assertTrue(ArticleType::ONE() === $type);
    $this->assertFalse(ArticleType::TWO() === $type);
  }
}
