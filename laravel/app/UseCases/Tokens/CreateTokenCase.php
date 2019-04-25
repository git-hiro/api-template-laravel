<?php

namespace App\UseCases\Tokens;

use App\Domains\Token;
use App\Domains\Translators\TokenTranslator;
use App\Repositories\Datasources\MultipleConnection;
use App\Repositories\ITokenRepository;
use App\Repositories\IUserRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CreateTokenCase
{
  /**
   * @var MultipleConnection
   */
  private $connection;

  /**
   * @var ITokenRepository
   */
  private $token_repository;

  /**
   * @var IUserRepository
   */
  private $user_repository;

  public function __construct(
    MultipleConnection $connection,
    ITokenRepository $token_repository,
    IUserRepository $user_repository
  ) {
    $this->connection = $connection;
    $this->token_repository = $token_repository;
    $this->user_repository = $user_repository;
  }

  public function __invoke(string $email, string $password): Token
  {
    return $this->connection->transaction(['pgsql'], function () use ($email, $password) {
      // find user
      $user = $this->user_repository->getItemByUnique($email);
      if (!$user) {
        throw new UnauthorizedHttpException('Bearer');
      }

      // create token
      $token = TokenTranslator::new();

      return $this->token_repository->create($token, $user->id);
    });
  }
}
