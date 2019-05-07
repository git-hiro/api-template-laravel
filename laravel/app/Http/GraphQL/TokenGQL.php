<?php

namespace App\Http\GraphQL;

use App\UseCases\Tokens\CreateTokenCase;
use App\UseCases\Tokens\DeleteTokenCase;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class TokenGQL extends BaseGQL
{
  protected $create_token_case;
  protected $delete_token_case;

  public function __construct(
    CreateTokenCase $create_token_case,
    DeleteTokenCase $delete_token_case
  ) {
    $this->create_token_case = $create_token_case;
    $this->delete_token_case = $delete_token_case;
  }

  public function createTokenResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $token = $this->create_token_case($args['email'], $args['password']);

    return ['token' => $token];
  }

  public function deleteTokenResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();

    $this->delete_token_case($args['id'], $executor_id);

    return ['ok' => true];
  }
}
