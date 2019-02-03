<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="UserRequest",
 *   required={"name", "email"},
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="email", type="string"),
 *   @OA\Property(property="password", type="string"),
 * )
 */
class UserRequest extends FormRequest
{
  public function rules()
  {
    return [
      'name'    => 'required',
      'email'   => 'required',
      'password'=> 'required',
    ];
  }
}
