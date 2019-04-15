<?php

namespace Tests\RepositoryUnit;

use App\Domains\Translators\UserTranslator;
use App\Domains\User;
use App\Repositories\Datasources\DB\ArticleModel;
use App\Repositories\Datasources\DB\CommentModel;
use App\Repositories\Datasources\DB\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;

/**
 * @internal
 * @coversNothing
 */
class UserRepositoryTest extends RepositoryTestCase
{
  protected $user_id_01 = '9e20291e-5892-42ac-9013-6cb46cafc27f';
  protected $user_name_01 = 'user_name_01';
  protected $user_email_01 = 'user_email_01@test.localhost';

  protected $user_id_02 = '3bc0ff7c-6e60-421d-a077-b7ba9f336e1b';
  protected $user_name_02 = 'user_name_02';
  protected $user_email_02 = 'user_email_02@test.localhost';

  protected $user_id_03 = '14301f9b-6886-4842-a2a0-85998939007c';
  protected $user_email_03 = 'user_email_03@test.localhost';

  protected $article_id_01 = '2d9d9ba9-c6f2-45b9-83e1-8bd54a0580d7';

  protected $executor_id = '2e5fe7c2-1cb0-4559-9cc5-2ccddec27f04';

  public function setUp(): void
  {
    parent::setUp();

    $this->truncate(UserModel::class);
    $this->truncate(ArticleModel::class);
    $this->truncate(CommentModel::class);

    factory(UserModel::class)->create([
      'id'    => $this->user_id_01,
      'name'  => $this->user_name_01,
      'email' => $this->user_email_01,
    ]);
    factory(UserModel::class)->create([
      'id'    => $this->user_id_02,
      'name'  => $this->user_name_02,
      'email' => $this->user_email_02,
    ]);
    factory(UserModel::class)->create([
      'id'         => $this->user_id_03,
      'email'      => $this->user_email_03,
      'deleted_at' => new Carbon(),
    ]);
    factory(ArticleModel::class)->create([
      'id'      => $this->article_id_01,
      'user_id' => $this->user_id_01,
    ]);
    factory(ArticleModel::class)->create([
      'user_id' => $this->user_id_01,
    ]);
    factory(ArticleModel::class)->create([
      'user_id'    => $this->user_id_01,
      'deleted_at' => new Carbon(),
    ]);
    factory(CommentModel::class)->create([
      'user_id'    => $this->user_id_01,
      'article_id' => $this->article_id_01,
    ]);
    factory(CommentModel::class)->create([
      'user_id'    => $this->user_id_01,
      'article_id' => $this->article_id_01,
    ]);
    factory(CommentModel::class)->create([
      'user_id'    => $this->user_id_01,
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
    $repository = new UserRepository();

    $users = $repository->getList([]);
    $this->assertCount(2, $users);
  }

  public function testGetItem()
  {
    $repository = new UserRepository();

    $user = $repository->getItem($this->user_id_01, ['articles.comments', 'comments']);
    $this->assertSame($user->id, $this->user_id_01);
    $this->assertSame($user->name, $this->user_name_01);
    $this->assertSame($user->email, $this->user_email_01);
    $this->assertCount(2, $user->articles);
    $this->assertCount(2, $user->articles[0]->comments);
    $this->assertCount(2, $user->comments);

    $user = $repository->getItem($this->user_id_03, ['articles.comments', 'comments']);
    $this->assertNull($user);
  }

  public function testGetItemByUnique()
  {
    $repository = new UserRepository();

    $user = $repository->getItemByUnique($this->user_email_01, ['articles.comments', 'comments']);
    $this->assertSame($user->id, $this->user_id_01);
    $this->assertSame($user->name, $this->user_name_01);
    $this->assertSame($user->email, $this->user_email_01);

    $user = $repository->getItemByUnique($this->user_email_03, ['articles.comments', 'comments']);
    $this->assertNull($user);
  }

  public function testCreate()
  {
    $repository = new UserRepository();

    $name = 'name';
    $email = 'email@test.localhost';
    $password = 'password';

    $user = $repository->create(UserTranslator::new([
      'name'     => $name,
      'email'    => $email,
      'password' => $password,
    ]), $this->executor_id);
    $this->assertNotNull($user->id);
    $this->assertSame($user->name, $name);
    $this->assertSame($user->email, $email);
    $this->assertNull($user->password);
  }

  public function testUpdate()
  {
    $repository = new UserRepository();

    $name = 'name';
    $email = 'email@test.localhost';
    $password = 'password';

    $user = $repository->update($this->user_id_01, new User([
      'name'     => $name,
      'email'    => $email,
      'password' => $password,
    ]), $this->executor_id);
    $this->assertSame($user->id, $this->user_id_01);
    $this->assertSame($user->name, $name);
    $this->assertSame($user->email, $email);
    $this->assertNull($user->password);
  }

  public function testDelete()
  {
    $repository = new UserRepository();

    $repository->delete($this->user_id_01, $this->executor_id);
    $user = $repository->getItem($this->user_id_01);
    $this->assertNull($user);
  }
}
