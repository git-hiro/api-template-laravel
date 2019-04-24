<?php

namespace App\Http\GraphQL;

use App\Domains\Translators\UserTranslator;
use App\UseCases\Users\CreateUserCase;
use App\UseCases\Users\DeleteUserCase;
use App\UseCases\Users\GetUserCase;
use App\UseCases\Users\GetUserListCase;
use App\UseCases\Users\UpdateUserCase;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserGQL extends BaseGQL
{
  protected const RELS = [
    'articles',
    'comments',
  ];

  protected $get_user_list_case;
  protected $get_user_case;
  protected $create_user_case;
  protected $update_user_case;
  protected $delete_user_case;

  public function __construct(
    GetUserListCase $get_user_list_case,
    GetUserCase $get_user_case,
    CreateUserCase $create_user_case,
    UpdateUserCase $update_user_case,
    DeleteUserCase $delete_user_case
  ) {
    $this->get_user_list_case = $get_user_list_case;
    $this->get_user_case = $get_user_case;
    $this->create_user_case = $create_user_case;
    $this->update_user_case = $update_user_case;
    $this->delete_user_case = $delete_user_case;
  }

  public function usersResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'users', self::RELS);

    return $this->get_user_list_case($rels);
  }

  public function userResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'user', self::RELS);

    return $this->get_user_case($args['id'], $rels);
  }

  public function createUserResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();
    $user_req = UserTranslator::ofArray($args['user']);

    $user = $this->create_user_case($user_req, $executor_id);

    return ['user' => $user];
  }

  public function updateUserResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();
    $user_req = UserTranslator::ofArray($args['user']);

    $user = $this->update_user_case($args['id'], $user_req, $executor_id);

    return ['user' => $user];
  }

  public function deleteUserResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();

    $this->delete_user_case($args['id'], $executor_id);

    return ['ok' => true];
  }
}
