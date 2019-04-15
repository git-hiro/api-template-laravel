<?php

namespace Tests\RepositoryUnit;

use App\Domains\Article;
use App\Domains\Translators\ArticleTranslator;
use App\Repositories\ArticleRepository;
use App\Repositories\Datasources\DB\ArticleModel;
use App\Repositories\Datasources\DB\CommentModel;
use App\Repositories\Datasources\DB\UserModel;
use Illuminate\Support\Carbon;

/**
 * @covers \App\Repositories\ArticleRepository
 * @covers \App\Repositories\Datasources\DB\ArticleModel
 *
 * @internal
 */
class ArticleRepositoryTest extends RepositoryTestCase
{
  protected $user_id = '9e20291e-5892-42ac-9013-6cb46cafc27f';

  protected $article_id_01 = '2d9d9ba9-c6f2-45b9-83e1-8bd54a0580d7';
  protected $article_subject_01 = 'test_subject_01';
  protected $article_content_01 = 'test_content_01';

  protected $article_id_02 = '037ee0a7-5082-426f-a15e-e94645594250';
  protected $article_id_03 = '19b1c3be-2a98-477c-803a-00164a294576';

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
      'id'      => $this->article_id_01,
      'user_id' => $this->user_id,
      'subject' => $this->article_subject_01,
      'content' => $this->article_content_01,
    ]);
    factory(ArticleModel::class)->create([
      'id'      => $this->article_id_02,
      'user_id' => $this->user_id,
    ]);
    factory(ArticleModel::class)->create([
      'id'         => $this->article_id_03,
      'user_id'    => $this->user_id,
      'deleted_at' => new Carbon(),
    ]);

    factory(CommentModel::class)->create([
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id_01,
    ]);
    factory(CommentModel::class)->create([
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id_01,
    ]);
    factory(CommentModel::class)->create([
      'user_id'    => $this->user_id,
      'article_id' => $this->article_id_01,
      'deleted_at' => new Carbon(),
    ]);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testGetList()
  {
    $repository = new ArticleRepository();

    $articles = $repository->getList([]);
    $this->assertCount(2, $articles);
  }

  public function testGetItem()
  {
    $repository = new ArticleRepository();

    $article = $repository->getItem($this->article_id_01, ['user', 'comments']);
    $this->assertSame($article->id, $this->article_id_01);
    $this->assertSame($article->user_id, $this->user_id);
    $this->assertSame($article->subject, $this->article_subject_01);
    $this->assertSame($article->content, $this->article_content_01);
    $this->assertNotNull($article->user);
    $this->assertCount(2, $article->comments);

    $article = $repository->getItem($this->article_id_03, ['user', 'comments']);
    $this->assertNull($article);
  }

  public function testCreate()
  {
    $repository = new ArticleRepository();

    $subject = 'create_subject';
    $content = 'create_content';

    $article = $repository->create(ArticleTranslator::new([
      'user_id' => $this->user_id,
      'subject' => $subject,
      'content' => $content,
    ]), $this->executor_id);
    $this->assertNotNull($article->id);
    $this->assertSame($article->user_id, $this->user_id);
    $this->assertSame($article->subject, $subject);
    $this->assertSame($article->content, $content);
  }

  public function testUpdate()
  {
    $repository = new ArticleRepository();

    $subject = 'update_subject';
    $content = 'update_content';

    $article = $repository->update($this->article_id_01, new Article([
      'subject' => $subject,
      'content' => $content,
    ]), $this->executor_id);
    $this->assertSame($article->id, $this->article_id_01);
    $this->assertSame($article->subject, $subject);
    $this->assertSame($article->content, $content);
  }

  public function testDelete()
  {
    $repository = new ArticleRepository();

    $repository->delete($this->article_id_01, $this->executor_id);
    $article = $repository->getItem($this->article_id_01);
    $this->assertNull($article);
  }
}
