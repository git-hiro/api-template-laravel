<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class GetUserRequest extends FormRequest
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
    $data['id'] = $this->route('user');

    return $data;
  }
}
