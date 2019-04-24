<?php

namespace App\Http\GraphQL;

use App\Domains\Translators\CommentTranslator;
use App\UseCases\Comments\DeleteCommentCase;
use App\UseCases\Comments\GetCommentCase;
use App\UseCases\Comments\UpdateCommentCase;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CommentGQL extends BaseGQL
{
  protected const RELS = [
    'user',
    'article',
  ];

  protected $get_comment_case;
  protected $update_comment_case;
  protected $delete_comment_case;

  public function __construct(
    GetCommentCase $get_comment_case,
    UpdateCommentCase $update_comment_case,
    DeleteCommentCase $delete_comment_case
  ) {
    $this->get_comment_case = $get_comment_case;
    $this->update_comment_case = $update_comment_case;
    $this->delete_comment_case = $delete_comment_case;
  }

  public function comment_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'comment', self::RELS);

    return $this->get_comment_case($args['id'], $rels);
  }

  public function updateCommentResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();
    $comment_req = CommentTranslator::ofArray($args['comment']);

    return $this->update_comment_case($args['id'], $comment_req, $executor_id);
  }

  public function deleteCommentResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();

    return $this->delete_comment_case($args['id'], $executor_id);
  }
}
