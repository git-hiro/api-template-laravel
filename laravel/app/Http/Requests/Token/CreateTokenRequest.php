<?php

namespace App\Http\Requests\Token;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="CreateTokenRequest",
 *   type="object",
 *   required={"email", "password"},
 *   @OA\Property(property="email", type="string", format="email", example="test_user_01@test.localhost"),
 *   @OA\Property(property="password", type="string", example="password"),
 * )
 */
class CreateTokenRequest extends FormRequest
{
  public function rules()
  {
    return [
      'email'    => 'required|email',
      'password' => 'required',
    ];
  }
}
