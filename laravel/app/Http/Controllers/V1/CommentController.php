<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\CommentTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\GetCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\UseCases\Comments\DeleteCommentCase;
use App\UseCases\Comments\GetCommentCase;
use App\UseCases\Comments\UpdateCommentCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CommentController extends Controller
{
  /**
   * @OA\Parameter(
   *   name="comment_id",
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
   *   path="/api/v1/comments/{comment_id}",
   *   tags={"comments"},
   *   @OA\Parameter(
   *     ref="#/components/parameters/comment_id",
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="comment",
   *         ref="#/components/schemas/Comment"
   *       ),
   *     )
   *   )
   * )
   */
  public function show(GetCommentCase $case, GetCommentRequest $request)
  {
    $data = $request->validated();
    $comment = $case($data['id']);

    return new JsonResponse(['comment' => $comment], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/comments/{comment_id}",
   *   tags={"comments"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/comment_id",
   *   ),
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/UpdateCommentRequest",
   *     )
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="comment",
   *         ref="#/components/schemas/Comment"
   *       ),
   *     )
   *   )
   * )
   */
  public function update(UpdateCommentCase $case, UpdateCommentRequest $request)
  {
    $data = $request->validated();

    $executor_id = Str::uuid();

    $comment_req = CommentTranslator::ofArray($data['comment']);
    $comment = $case($data['id'], $comment_req, $executor_id);

    return new JsonResponse(['comment' => $comment], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/comments/{comment_id}",
   *   tags={"comments"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/comment_id",
   *   ),
   *   @OA\Response(response="204", description="",
   *   )
   * )
   */
  public function destroy(DeleteCommentCase $case, DeleteCommentRequest $request)
  {
    $data = $request->validated();

    $executor_id = Str::uuid();

    $case($data['id'], $executor_id);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
