<?php

namespace App\Http\GraphQL;

use App\Domains\Translators\ArticleTranslator;
use App\Domains\Translators\CommentTranslator;
use App\UseCases\Articles\CreateArticleCase;
use App\UseCases\Articles\CreateArticleCommentCase;
use App\UseCases\Articles\DeleteArticleCase;
use App\UseCases\Articles\GetArticleCase;
use App\UseCases\Articles\GetArticleListCase;
use App\UseCases\Articles\UpdateArticleCase;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleGQL extends BaseGQL
{
  protected const RELS = [
    'user',
    'comments',
  ];

  protected $get_article_list_case;
  protected $get_article_case;
  protected $create_article_case;
  protected $update_article_case;
  protected $delete_article_case;
  protected $create_article_comment_case;

  public function __construct(
    GetArticleListCase $get_article_list_case,
    GetArticleCase $get_article_case,
    CreateArticleCase $create_article_case,
    UpdateArticleCase $update_article_case,
    DeleteArticleCase $delete_article_case,
    CreateArticleCommentCase $create_article_comment_case
  ) {
    $this->get_article_list_case = $get_article_list_case;
    $this->get_article_case = $get_article_case;
    $this->create_article_case = $create_article_case;
    $this->update_article_case = $update_article_case;
    $this->delete_article_case = $delete_article_case;
    $this->create_article_comment_case = $create_article_comment_case;
  }

  public function articlesResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'articles', self::RELS);

    return $this->get_article_list_case($rels);
  }

  public function articleResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'article', self::RELS);

    return $this->get_article_case($args['id'], $rels);
  }

  public function createArticleResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();
    $article_req = ArticleTranslator::ofArray($args['article']);

    return $this->create_article_case($article_req, $executor_id);
  }

  public function updateArticleResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();
    $article_req = ArticleTranslator::ofArray($args['article']);

    return $this->update_article_case($args['id'], $article_req, $executor_id);
  }

  public function deleteArticleResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();

    return $this->delete_article_case($args['id'], $executor_id);
  }

  public function createArticleCommentResolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $executor_id = Str::uuid();
    $comment_req = CommentTranslator::ofArray($args['comment']);

    return $this->create_article_comment_case($args['article_id'], $comment_req);
  }
}
