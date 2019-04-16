<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="CreateCommentRequest",
 *   type="object",
 *   @OA\Property(
 *     property="comment",
 *     required={"content"},
 *     @OA\Property(property="content", type="string"),
 *   ),
 * )
 */
class CreateCommentRequest extends FormRequest
{
  public function rules()
  {
    return [
      'comment'         => 'required',
      'comment.content' => 'required',
    ];
  }
}
