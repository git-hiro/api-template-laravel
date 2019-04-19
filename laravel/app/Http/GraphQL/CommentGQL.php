<?php

namespace App\Http\GraphQL;

use App\UseCases\Comments\GetCommentCase;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CommentGQL extends BaseGQL
{
  protected const RELS = [
    'user',
    'article',
  ];

  protected $case;

  public function __construct(
    GetCommentCase $get_comment_case
  ) {
    $this->get_comment_case = $get_comment_case;
  }

  public function comment_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'comment', self::RELS);

    return $this->get_comment_case($args['id'], $rels);
  }
}
