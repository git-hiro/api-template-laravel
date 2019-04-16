<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class GetCommentRequest extends FormRequest
{
  public function rules()
  {
    return [
      'id' => 'required|uuid',
    ];
  }

  protected function validationData()
  {
    $data = parent::validationData();
    $data['id'] = $this->route('comment');

    return $data;
  }
}
