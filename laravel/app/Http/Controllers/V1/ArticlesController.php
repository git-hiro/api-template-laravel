<?php

namespace App\Http\Controllers\V1;

use App\Domains\Translators\ArticleTranslator;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\UseCases\Articles\CreateArticleCase;
use App\UseCases\Articles\DeleteArticleCase;
use App\UseCases\Articles\GetArticleCase;
use App\UseCases\Articles\GetArticleListCase;
use App\UseCases\Articles\UpdateArticleCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *   name="articles",
 *   description="",
 * )
 */
class ArticlesController extends Controller
{
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
   *   path="/api/v1/articles/{articleId}",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     name="articleId",
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
   *         property="article",
   *         ref="#/components/schemas/Article"
   *       ),
   *     )
   *   )
   * )
   */
  public function show(GetArticleCase $case, string $id)
  {
    $article = $case($id);

    return new JsonResponse(['article' => $article], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Post(
   *   path="/api/v1/articles",
   *   tags={"articles"},
   *   @OA\RequestBody(description="",
   *     @OA\JsonContent(
   *       type="object",
   *       @OA\Property(
   *         property="article",
   *         ref="#/components/schemas/Article"
   *       ),
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
  public function store(CreateArticleCase $case, ArticleRequest $request)
  {
    $data = $request->validated();

    $executorId = Str::uuid();

    $articleReq = ArticleTranslator::ofArray($data['article']);
    $article = $case($articleReq, $executorId);

    return new JsonResponse(['article' => $article], JsonResponse::HTTP_CREATED);
  }

  /**
   * @OA\Put(
   *   path="/api/v1/articles/{articleId}",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     name="articleId",
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
   *         property="article",
   *         ref="#/components/schemas/Article"
   *       ),
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
  public function update(UpdateArticleCase $case, string $id, ArticleRequest $request)
  {
    $data = $request->validate([
      'article'       => 'required',
      'article.name'  => 'required',
      'article.email' => 'required|email',
    ]);

    $executorId = Str::uuid();

    $articleReq = ArticleTranslator::ofArray($data['article']);
    $article = $case($id, $articleReq, $executorId);

    return new JsonResponse(['article' => $article], JsonResponse::HTTP_OK);
  }

  /**
   * @OA\Delete(
   *   path="/api/v1/articles/{articleId}",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     name="articleId",
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
  public function destroy(DeleteArticleCase $case, string $id)
  {
    $executorId = Str::uuid();

    $case($id, $executorId);

    return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  /**
   * @OA\Get(
   *   path="/api/v1/articles/{articleId}/comments",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     name="articleId",
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

  /**
   * @OA\Post(
   *   path="/api/v1/articles/{articleId}/comments",
   *   tags={"articles"},
   *   @OA\Parameter(
   *     name="articleId",
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
  public function storeComment(CreateCommentCase $case, CommentRequest $request)
  {
    $data = $request->validated();

    $executorId = Str::uuid();

    $commentReq = CommentTranslator::ofArray($data['comment']);
    $comment = $case($commentReq, $executorId);

    return new JsonResponse(['comment' => $comment], JsonResponse::HTTP_CREATED);
  }
}
