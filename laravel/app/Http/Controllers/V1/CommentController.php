<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\CommentTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\UseCases\Comments\DeleteCommentCase;
use App\UseCases\Comments\GetCommentCase;
use App\UseCases\Comments\UpdateCommentCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *   name="comments",
 *   description="",
 * )
 */
class CommentController extends Controller
{
  /**
   * @OA\Get(
   *   path="/api/v1/comments/{commentId}",
   *   tags={"comments"},
   *   @OA\Parameter(
   *     name="commentId",
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
   *         property="comment",
   *         ref="#/components/schemas/Comment"
   *       ),
   *     )
   *   )
   * )
   */
  public function show(GetCommentCase $case, string $id)
  {
    $comment = $case($id);

    return new JsonResponse(['comment' => $comment], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/comments/{commentId}",
   *   tags={"comments"},
   *   @OA\Parameter(
   *     name="commentId",
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
   *         property="comment",
   *         ref="#/components/schemas/Comment"
   *       ),
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
  public function update(UpdateCommentCase $case, string $id, CommentRequest $request)
  {
    $data = $request->validate([
      'comment'       => 'required',
      'comment.name'  => 'required',
      'comment.email' => 'required|email',
    ]);

    $executor_id = Str::uuid();

    $comment_req = CommentTranslator::ofArray($data['comment']);
    $comment = $case($id, $comment_req, $executor_id);

    return new JsonResponse(['comment' => $comment], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/comments/{commentId}",
   *   tags={"comments"},
   *   @OA\Parameter(
   *     name="commentId",
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
  public function destroy(DeleteCommentCase $case, string $id)
  {
    $executor_id = Str::uuid();

    $case($id, $executor_id);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
