<?php

namespace App\Http\GraphQL;

use App\UseCases\Articles\GetArticleCase;
use App\UseCases\Articles\GetArticleListCase;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleGQL extends BaseGQL
{
  protected const RELS = [
    'user',
    'comments',
  ];

  protected $case;

  public function __construct(
    GetArticleListCase $get_article_list_case,
    GetArticleCase $get_article_case
  ) {
    $this->get_article_list_case = $get_article_list_case;
    $this->get_article_case = $get_article_case;
  }

  public function articles_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'articles', self::RELS);

    return $this->get_article_list_case($rels);
  }

  public function article_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    $rels = Utils::getRelations($resolveInfo, 'article', self::RELS);

    return $this->get_article_case($args['id'], $rels);
  }
}
