<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Token\CreateTokenRequest;
use App\Http\Requests\Token\DeleteTokenRequest;
use App\UseCases\Tokens\CreateTokenCase;
use App\UseCases\Tokens\DeleteTokenCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
  /**
   * @OA\Post(
   *   path="/api/v1/tokens",
   *   tags={"tokens"},
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/CreateTokenRequest",
   *     )
   *   ),
   *   @OA\Response(response="201", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="token",
   *         ref="#/components/schemas/Token"
   *       ),
   *     )
   *   )
   * )
   */
  public function store(CreateTokenCase $case, CreateTokenRequest $request)
  {
    $data = $request->validated();

    $token = $case($data['email'], $data['password']);

    return new JsonResponse(['token' => $token], JsonResponse::HTTP_CREATED);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/tokens",
   *   tags={"tokens"},
   *   @OA\Response(response="204", description="",
   *   )
   * )
   */
  public function destroy(DeleteTokenCase $case, DeleteTokenRequest $request)
  {
    if (Auth::check()) {
      $data = $request->validated();
      \Log::debug($data);
      $case($data['Authentication']);
    }

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
