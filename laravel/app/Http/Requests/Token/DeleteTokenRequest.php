<?php

namespace App\Http\Requests\Token;

use Illuminate\Foundation\Http\FormRequest;

class DeleteTokenRequest extends FormRequest
{
  public function rules()
  {
    return [];
  }

  protected function validationData()
  {
    $data = parent::validationData();
    $data['Authentication'] = $this->headers->get('Authentication');

    return $data;
  }
}
