<?php

namespace App\Http\GraphQL;

use App\UseCases\Users\GetUserCase;
use App\UseCases\Users\GetUserListCase;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserGQL extends BaseGQL
{
  protected const RELS = [
    'articles',
    'comments',
  ];

  protected $case;

  public function __construct(
    GetUserListCase $get_user_list_case,
    GetUserCase $get_user_case
  ) {
    $this->get_user_list_case = $get_user_list_case;
    $this->get_user_case = $get_user_case;
  }

  public function users_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'users', self::RELS);

    return $this->get_user_list_case($rels);
  }

  public function user_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'user', self::RELS);

    return $this->get_user_case($args['id'], $rels);
  }
}
