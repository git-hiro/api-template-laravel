<?php

namespace App\Http\Requests\Token;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   title="CreateTokenRequest",
 *   type="object",
 *   required={"email", "password"},
 *   @OA\Property(property="email", type="string", format="email"),
 *   @OA\Property(property="password", type="string"),
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
