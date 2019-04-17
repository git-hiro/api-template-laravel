<?php

namespace App\GraphQL\Queries;

use App\GraphQL\BaseGQL;
use App\UseCases\Users\GetUserListCase;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Users extends BaseGQL
{
  protected $case;

  public function __construct(
    GetUserListCase $case
  ) {
    $this->case = $case;
  }

  public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    return $this->case();
  }
}
