<?php

namespace Tests\RepositoryUnit;

use App\Domains\Translators\TokenTranslator;
use App\Repositories\Datasources\DB\TokenModel;
use App\Repositories\Datasources\DB\UserModel;
use App\Repositories\TokenRepository;

/**
 * @covers \App\Domains\Translators\TokenTranslator
 * @covers \App\Repositories\Datasources\DB\TokenModel
 * @covers \App\Repositories\TokenRepository
 *
 * @internal
 */
class TokenRepositoryTest extends RepositoryTestCase
{
  protected $user_id = '9e20291e-5892-42ac-9013-6cb46cafc27f';

  protected $token_value_01 = '2d9d9ba9-c6f2-45b9-83e1-8bd54a0580d7';
  protected $token_value_02 = '037ee0a7-5082-426f-a15e-e94645594250';
  protected $token_value_03 = '19b1c3be-2a98-477c-803a-00164a294576';

  public function setUp(): void
  {
    parent::setUp();

    $this->truncate(UserModel::class);
    $this->truncate(TokenModel::class);

    factory(UserModel::class)->create([
      'id' => $this->user_id,
    ]);

    factory(TokenModel::class)->create([
      'value'   => $this->token_value_01,
      'user_id' => $this->user_id,
    ]);
    factory(TokenModel::class)->create([
      'value'   => $this->token_value_02,
      'user_id' => $this->user_id,
    ]);
  }

  public function tearDown(): void
  {
    parent::tearDown();
  }

  public function testGetItem()
  {
    $repository = new TokenRepository();

    $token = $repository->getItem($this->token_value_01, ['user']);
    $this->assertSame($token->value, $this->token_value_01);
    $this->assertSame($token->user_id, $this->user_id);
    $this->assertNotNull($token->user);

    $token = $repository->getItem($this->token_value_03, ['user']);
    $this->assertNull($token);
  }

  public function testCreate()
  {
    $repository = new TokenRepository();

    $token = $repository->create(TokenTranslator::new([
    ]), $this->user_id);
    $this->assertNotNull($token->value);
    $this->assertSame($token->user_id, $this->user_id);
  }

  public function testUpdate()
  {
    $repository = new TokenRepository();

    $token_before = $repository->getItem($this->token_value_01);

    $token = $repository->update($this->token_value_01);
    $this->assertSame($token->value, $this->token_value_01);
    $this->assertSame($token->user_id, $this->user_id);
  }

  public function testDelete()
  {
    $repository = new TokenRepository();

    $repository->delete($this->token_value_01);
    $token = $repository->getItem($this->token_value_01);
    $this->assertNull($token);
  }
}
