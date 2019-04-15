<?php

namespace Tests\RepositoryUnit;

use App\Domains\Comment;
use App\Domains\Translators\CommentTranslator;
use App\Repositories\CommentRepository;
use App\Repositories\Datasources\DB\ArticleModel;
use App\Repositories\Datasources\DB\CommentModel;
use App\Repositories\Datasources\DB\UserModel;
use Illuminate\Support\Carbon;

/**
 * @covers \App\Repositories\CommentRepository
 * @covers \App\Repositories\Datasources\DB\CommentModel
 *
 * @internal
 */
class CommentRepositoryTest extends RepositoryTestCase
{
  protected $user_id = '9e20291e-5892-42ac-9013-6cb46cafc27f';
  protected $article_id = 'b5b1c076-ad09-42be-a803-457809cd779f';

  protected $comment_id_01 = '2d9d9ba9-c6f2-45b9-83e1-8bd54a0580d7';
  protected $comment_content_01 = 'test_content_01';

  protected $comment_id_02 = '037ee0a7-5082-426f-a15e-e94645594250';
  protected $comment_id_03 = '19b1c3be-2a98-477c-803a-00164a294576';

  protected $executor_id = '2e5fe7c2-1cb0-4559-9cc5-2ccddec27f04';

  public function setUp(): void
  {
    parent::setUp();

    $this->truncate(UserModel::class);
    $this->truncate(ArticleModel::class);
    $this->truncate(CommentModel::class);

    factory(UserModel::class)->create([
      'id'    => $this->user_id,
    ]);

    factory(ArticleModel::class)->create([
      'id'      => $this->article_id,
      'user_id' => $this->user_id,
    ]);

    factory(CommentModel::class)->create([
      'id'         => $this->comment_id_01,
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id,
      'content'    => $this->comment_content_01,
    ]);
    factory(CommentModel::class)->create([
      'id'         => $this->comment_id_02,
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id,
    ]);
    factory(CommentModel::class)->create([
      'id'         => $this->comment_id_03,
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id,
      'deleted_at' => new Carbon(),
    ]);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testGetList()
  {
    $repository = new CommentRepository();

    $articles = $repository->getList([]);
    $this->assertCount(2, $articles);
  }

  public function testGetItem()
  {
    $repository = new CommentRepository();

    $comment = $repository->getItem($this->comment_id_01, ['user', 'article']);
    $this->assertSame($comment->id, $this->comment_id_01);
    $this->assertSame($comment->user_id, $this->user_id);
    $this->assertSame($comment->article_id, $this->article_id);
    $this->assertSame($comment->content, $this->comment_content_01);
    $this->assertNotNull($comment->user);
    $this->assertNotNull($comment->article);

    $comment = $repository->getItem($this->comment_id_03, ['user', 'article']);
    $this->assertNull($comment);
  }

  public function testCreate()
  {
    $repository = new CommentRepository();

    $content = 'create_content';

    $comment = $repository->create(CommentTranslator::new([
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id,
      'content'    => $content,
    ]), $this->executor_id);
    $this->assertNotNull($comment->id);
    $this->assertSame($comment->user_id, $this->user_id);
    $this->assertSame($comment->article_id, $this->article_id);
    $this->assertSame($comment->content, $content);
  }

  public function testUpdate()
  {
    $repository = new CommentRepository();

    $subject = 'update_subject';
    $content = 'update_content';

    $comment = $repository->update($this->comment_id_01, new Comment([
      'content' => $content,
    ]), $this->executor_id);
    $this->assertSame($comment->id, $this->comment_id_01);
    $this->assertSame($comment->content, $content);
  }

  public function testDelete()
  {
    $repository = new CommentRepository();

    $repository->delete($this->comment_id_01, $this->executor_id);
    $article = $repository->getItem($this->comment_id_01);
    $this->assertNull($article);
  }
}
