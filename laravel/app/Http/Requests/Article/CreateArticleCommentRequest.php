<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="CreateArticleCommentRequest",
 *   type="object",
 *   @OA\Property(
 *     property="comment",
 *     required={"content"},
 *     @OA\Property(property="content", type="string"),
 *   ),
 * )
 */
class CreateArticleCommentRequest extends FormRequest
{
  public function rules()
  {
    return [
      'id'              => 'required|uuid',
      'comment'         => 'required',
      'comment.content' => 'required',
    ];
  }

  protected function validationData()
  {
    $data = parent::validationData();
    $data['id'] = $this->route('article');

    return $data;
  }
}
