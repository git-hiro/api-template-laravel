<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\UserTranslator;
use App\Http\Controllers\Controller;
use App\UseCases\Users\CreateUserCase;
use App\UseCases\Users\DeleteUserCase;
use App\UseCases\Users\GetUserCase;
use App\UseCases\Users\GetUserListCase;
use App\UseCases\Users\UpdateUserCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;

/**
 * @OA\Tag(
 *   name="users",
 *   description="",
 * )
 */

/**
 * @Resource("api/v1/users", only={"index", "show", "store", "update", "destroy"})
 */
class UsersController extends Controller
{
  /**
   * @OA\Get(
   *   path="/api/v1/users",
   *   tags={"users"},
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="users",
   *         type="array",
   *         @OA\Items(ref="#/components/schemas/User"),
   *       )
   *     )
   *   )
   * )
   */
  public function index(GetUserListCase $case)
  {
    $users = $case();

    return new JsonResponse(['users' => $users], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/users/{id}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="",
   *     required=true,
   *     @OA\Schema(
   *       type="string",
   *     )
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="user",
   *         ref="#/components/schemas/User"
   *       ),
   *     )
   *   )
   * )
   */
  public function show(GetUserCase $case, string $id)
  {
    $user = $case($id);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Post(
   *   path="/api/v1/users",
   *   tags={"users"},
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="user",
   *         ref="#/components/schemas/User"
   *       ),
   *     )
   *   ),
   *   @OA\Response(response="201", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="user",
   *         ref="#/components/schemas/User"
   *       ),
   *     )
   *   )
   * )
   */
  public function store(CreateUserCase $case, Request $request)
  {
    $data = $request->validate([
      'user'          => 'required',
      'user.name'     => 'required',
      'user.email'    => 'required|email',
      'user.password' => 'required',
    ]);

    $id = Uuid::generate(4)->string;

    $userReq = UserTranslator::ofArray($data['user']);
    $user = $case($userReq, $id);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_CREATED);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/users/{id}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="",
   *     required=true,
   *     @OA\Schema(
   *       type="string",
   *     )
   *   ),
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="user",
   *         ref="#/components/schemas/User"
   *       ),
   *     )
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="user",
   *         ref="#/components/schemas/User"
   *       ),
   *     )
   *   )
   * )
   */
  public function update(UpdateUserCase $case, string $id, Request $request)
  {
    $data = $request->validate([
      'user'       => 'required',
      'user.name'  => 'required',
      'user.email' => 'required|email',
    ]);

    $executorId = Uuid::generate(4)->string;

    $userReq = UserTranslator::ofArray($data['user']);
    $user = $case($id, $userReq, $executorId);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/users/{id}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="",
   *     required=true,
   *     @OA\Schema(
   *       type="string",
   *     )
   *   ),
   *   @OA\Response(response="204", description="",
   *   )
   * )
   */
  public function destroy(DeleteUserCase $case, string $id)
  {
    $executorId = Uuid::generate(4)->string;

    $case($id, $executorId);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
