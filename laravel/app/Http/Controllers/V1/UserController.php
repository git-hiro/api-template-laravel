<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\UserTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\UseCases\Users\CreateUserCase;
use App\UseCases\Users\DeleteUserCase;
use App\UseCases\Users\GetUserCase;
use App\UseCases\Users\GetUserListCase;
use App\UseCases\Users\UpdateUserCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class UserController extends Controller
{
  /**
   * @OA\Parameter(
   *   name="user_id",
   *   in="path",
   *   description="",
   *   required=true,
   *   @OA\Schema(
   *     type="string",
   *   ),
   * )
   */

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
   *   path="/api/v1/users/{user_id}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     ref="#/components/parameters/user_id",
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
  public function show(GetUserCase $case, GetUserRequest $request)
  {
    $data = $request->validated();
    $user = $case($data['id']);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Post(
   *   path="/api/v1/users",
   *   tags={"users"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/CreateUserRequest",
   *     ),
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
  public function store(CreateUserCase $case, CreateUserRequest $request)
  {
    $data = $request->validated();

    $executor_id = Str::uuid();

    $user_req = UserTranslator::ofArray($data['user']);
    $user = $case($user_req, $executor_id);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_CREATED);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/users/{user_id}",
   *   tags={"users"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/user_id",
   *   ),
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/UpdateUserRequest",
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
  public function update(UpdateUserCase $case, UpdateUserRequest $request)
  {
    $data = $request->validated();

    $executor_id = Str::uuid();

    $user_req = UserTranslator::ofArray($data['user']);
    $user = $case($data['id'], $user_req, $executor_id);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/users/{user_id}",
   *   tags={"users"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/user_id",
   *   ),
   *   @OA\Response(response="204", description="",
   *   )
   * )
   */
  public function destroy(DeleteUserCase $case, DeleteUserRequest $request)
  {
    $data = $request->validated();

    $executor_id = Str::uuid();

    $case($data['id'], $executor_id);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/users/{user_id}/articles",
   *   tags={"users"},
   *   @OA\Parameter(
   *     ref="#/components/parameters/user_id",
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="articles",
   *         type="array",
   *         @OA\Items(ref="#/components/schemas/Article"),
   *       )
   *     )
   *   )
   * )
   */
  public function indexArticles(GetUserCase $case, GetUserRequest $request)
  {
    $data = $request->validated();

    $user = $case($data['id'], ['comments']);

    return new JsonResponse(['articles' => $user->articles], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/users/{user_id}/comments",
   *   tags={"users"},
   *   @OA\Parameter(
   *     ref="#/components/parameters/user_id",
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="comments",
   *         type="array",
   *         @OA\Items(ref="#/components/schemas/Comment"),
   *       )
   *     )
   *   )
   * )
   */
  public function indexComments(GetUserCase $case, GetUserRequest $request)
  {
    $data = $request->validated();

    $user = $case($data['id'], ['comments']);

    return new JsonResponse(['comments' => $user->comments], JsonResponse::HTTP_OK);
  }
}
