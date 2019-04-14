<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="UpdateUserRequest",
 *   required={"name", "email", "password"},
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="email", type="string"),
 *   @OA\Property(property="password", type="string"),
 * )
 */
class UpdateUserRequest extends FormRequest
{
  public function rules()
  {
    return [
      'id'            => 'required|uuid',
      'user'          => 'required',
      'user.name'     => 'required',
      'user.email'    => 'required|email',
      'user.password' => 'filled',
    ];
  }

  protected function validationData()
  {
    $all = parent::all();
    $all['id'] = $this->route('user');

    \Log::debug($all);

    return $all;
  }
}
