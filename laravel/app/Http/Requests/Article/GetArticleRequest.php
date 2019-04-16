<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class GetArticleRequest extends FormRequest
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
    $data['id'] = $this->route('article');

    return $data;
  }
}
