<?php

namespace App\Http\GraphQL;

use App\UseCases\Articles\GetArticleCase;
use App\UseCases\Articles\GetArticleListCase;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ArticleGQL extends BaseGQL
{
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
    return $this->get_article_list_case();
  }

  public function article_resolver($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
  {
    return $this->get_article_case($args['id']);
  }
}
