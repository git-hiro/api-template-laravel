<?php

namespace App\Http\GraphQL\Types;

use Exception;
use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class UUID extends ScalarType
{
  public function serialize($value): string
  {
    return $this->tryParsingUuid($value, InvariantViolation::class);
  }

  public function parseValue($value): string
  {
    return $this->tryParsingUuid($value, Error::class);
  }

  public function parseLiteral($valueNode, ?array $variables = null): string
  {
    if (!$valueNode instanceof StringValueNode) {
      throw new Error(
      "Query error: Can only parse strings, got {$valueNode->kind}",
      [$valueNode]
  );
    }

    return $this->tryParsingUuid($valueNode->value, Error::class);
  }

  protected function tryParsingUuid($value, string $exceptionClass): string
  {
    try {
      // TODO: Check UUID Format
      return $value;
    } catch (Exception $e) {
      throw new $exceptionClass(
      Utils::printSafeJson($e->getMessage())
  );
    }
  }
}
