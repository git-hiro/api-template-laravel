<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="CreateArticleRequest",
 *   type="object",
 *   @OA\Property(
 *     property="article",
 *     required={"subject", "content"},
 *     @OA\Property(property="subject", type="string"),
 *     @OA\Property(property="content", type="string"),
 *   ),
 * )
 */
class CreateArticleRequest extends FormRequest
{
  public function rules()
  {
    return [
      'article'         => 'required',
      'article.subject' => 'required',
      'article.content' => 'required',
    ];
  }
}
