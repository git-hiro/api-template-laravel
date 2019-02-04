<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\CommentTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\UseCases\Comments\CreateCommentCase;
use App\UseCases\Comments\DeleteCommentCase;
use App\UseCases\Comments\GetCommentCase;
use App\UseCases\Comments\GetCommentListCase;
use App\UseCases\Comments\UpdateCommentCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *   name="comments",
 *   description="",
 * )
 */
class CommentsController extends Controller
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

    $executorId = Str::uuid();

    $commentReq = CommentTranslator::ofArray($data['comment']);
    $comment = $case($id, $commentReq, $executorId);

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
    $executorId = Str::uuid();

    $case($id, $executorId);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }
}
