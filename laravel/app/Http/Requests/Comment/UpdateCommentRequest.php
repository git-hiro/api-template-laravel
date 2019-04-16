<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="UpdateCommentRequest",
 *   type="object",
 *   @OA\Property(
 *     property="comment",
 *     required={"content"},
 *     @OA\Property(property="content", type="string"),
 *   ),
 * )
 */
class UpdateCommentRequest extends FormRequest
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
    $data['id'] = $this->route('comment');

    return $data;
  }
}
