<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="StoreUserRequest",
 *   required={"name", "email", "password"},
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="email", type="string"),
 *   @OA\Property(property="password", type="string"),
 * )
 */
class StoreUserRequest extends FormRequest
{
  public function rules()
  {
    return [
      'user'          => 'required',
      'user.name'     => 'required',
      'user.email'    => 'required|email',
      'user.password' => 'required',
    ];
  }
}
