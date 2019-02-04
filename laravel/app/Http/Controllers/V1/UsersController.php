<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\UserTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\UseCases\Users\CreateUserCase;
use App\UseCases\Users\DeleteUserCase;
use App\UseCases\Users\GetUserCase;
use App\UseCases\Users\GetUserListCase;
use App\UseCases\Users\UpdateUserCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *   name="users",
 *   description="",
 * )
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
   *   path="/api/v1/users/{userId}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="userId",
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
  public function store(CreateUserCase $case, UserRequest $request)
  {
    $data = $request->validated();

    $executorId = Str::uuid();

    $userReq = UserTranslator::ofArray($data['user']);
    $user = $case($userReq, $executorId);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_CREATED);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/users/{userId}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="userId",
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
  public function update(UpdateUserCase $case, string $id, UserRequest $request)
  {
    $data = $request->validate([
      'user'       => 'required',
      'user.name'  => 'required',
      'user.email' => 'required|email',
    ]);

    $executorId = Str::uuid();

    $userReq = UserTranslator::ofArray($data['user']);
    $user = $case($id, $userReq, $executorId);

    return new JsonResponse(['user' => $user], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/users/{userId}",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="userId",
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
    $executorId = Str::uuid();

    $case($id, $executorId);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/users/{userId}/articles",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="userId",
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
   *         property="comments",
   *         type="array",
   *         @OA\Items(ref="#/components/schemas/Article"),
   *       )
   *     )
   *   )
   * )
   */
  public function indexArticles(GetArticleListCase $case)
  {
    $comments = $case();

    return new JsonResponse(['comments' => $comments], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/users/{userId}/comments",
   *   tags={"users"},
   *   @OA\Parameter(
   *     name="userId",
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
   *         property="comments",
   *         type="array",
   *         @OA\Items(ref="#/components/schemas/Comment"),
   *       )
   *     )
   *   )
   * )
   */
  public function indexComments(GetCommentListCase $case)
  {
    $comments = $case();

    return new JsonResponse(['comments' => $comments], JsonResponse::HTTP_OK);
  }
}
