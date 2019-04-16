<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="UpdateArticleRequest",
 *   type="object",
 *   @OA\Property(
 *     property="article",
 *     required={"subject", "content"},
 *     @OA\Property(property="subject", type="string"),
 *     @OA\Property(property="content", type="string"),
 *   ),
 * )
 */
class UpdateArticleRequest extends FormRequest
{
  public function rules()
  {
    return [
      'id'              => 'required|uuid',
      'article'         => 'required',
      'article.subject' => 'required',
      'article.content' => 'required',
    ];
  }

  protected function validationData()
  {
    $data = parent::validationData();
    $data['id'] = $this->route('article');

    return $data;
  }
}
