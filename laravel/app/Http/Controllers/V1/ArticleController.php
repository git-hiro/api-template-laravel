<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\ArticleTranslator;
use App\Domains\Translators\CommentTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\CreateArticleCommentRequest;
use App\Http\Requests\Article\CreateArticleRequest;
use App\Http\Requests\Article\DeleteArticleRequest;
use App\Http\Requests\Article\GetArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\UseCases\Articles\CreateArticleCase;
use App\UseCases\Articles\CreateArticleCommentCase;
use App\UseCases\Articles\DeleteArticleCase;
use App\UseCases\Articles\GetArticleCase;
use App\UseCases\Articles\GetArticleListCase;
use App\UseCases\Articles\UpdateArticleCase;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
  /**
   * @OA\Parameter(
   *   name="article_id",
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
   *   path="/api/v1/articles",
   *   tags={"articles"},
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
  public function index(GetArticleListCase $case)
  {
    $articles = $case();

    return new JsonResponse(['articles' => $articles], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/articles/{article_id}",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     ref="#/components/parameters/article_id",
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="article",
   *         ref="#/components/schemas/Article"
   *       ),
   *     )
   *   )
   * )
   */
  public function show(GetArticleCase $case, GetArticleRequest $request)
  {
    $data = $request->validated();
    $article = $case($data['id']);

    return new JsonResponse(['article' => $article], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Post(
   *   path="/api/v1/articles",
   *   tags={"articles"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/CreateArticleRequest",
   *     )
   *   ),
   *   @OA\Response(response="201", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="article",
   *         ref="#/components/schemas/Article"
   *       ),
   *     )
   *   )
   * )
   */
  public function store(CreateArticleCase $case, CreateArticleRequest $request)
  {
    $data = $request->validated();

    $executor_id = \Auth::id();

    $article_req = ArticleTranslator::ofArray($data['article']);
    $article = $case($article_req, $executor_id);

    return new JsonResponse(['article' => $article], JsonResponse::HTTP_CREATED);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/articles/{article_id}",
   *   tags={"articles"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/article_id",
   *   ),
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/UpdateArticleRequest",
   *     )
   *   ),
   *   @OA\Response(response="200", description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="article",
   *         ref="#/components/schemas/Article"
   *       ),
   *     )
   *   )
   * )
   */
  public function update(UpdateArticleCase $case, UpdateArticleRequest $request)
  {
    $data = $request->validated();

    $executor_id = \Auth::id();

    $article_req = ArticleTranslator::ofArray($data['article']);
    $article = $case($data['id'], $article_req, $executor_id);

    return new JsonResponse(['article' => $article], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/articles/{article_id}",
   *   tags={"articles"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/article_id",
   *   ),
   *   @OA\Response(response="204", description="",
   *   )
   * )
   */
  public function destroy(DeleteArticleCase $case, DeleteArticleRequest $request)
  {
    $data = $request->validated();

    $executor_id = \Auth::id();

    $case($data['id'], $executor_id);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/articles/{article_id}/comments",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     ref="#/components/parameters/article_id",
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
  public function indexComments(GetArticleCase $case, GetArticleRequest $request)
  {
    $data = $request->validated();

    $article = $case($data['id'], ['comments']);

    return new JsonResponse(['comments' => $comments], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Post(
   *   path="/api/v1/articles/{article_id}/comments",
   *   tags={"articles"},
   *   security={
   *     {"token": {}}
   *   },
   *   @OA\Parameter(
   *     ref="#/components/parameters/article_id",
   *   ),
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       ref="#/components/schemas/CreateArticleCommentRequest",
   *     )
   *   ),
   *   @OA\Response(response="201", description="",
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
  public function storeComment(CreateArticleCommentCase $case, CreateArticleCommentRequest $request)
  {
    $data = $request->validated();

    $executor_id = \Auth::id();

    $comment_req = CommentTranslator::ofArray($data['comment']);
    $comment = $case($data['id'], $comment_req, $executor_id);

    return new JsonResponse(['comment' => $comment], JsonResponse::HTTP_CREATED);
  }
}
