<?php

namespace App\Auth;

use App\Repositories\ITokenRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class TokenGuard implements Guard
{
  protected $token_repository;
  protected $request;

  protected $user;

  public function __construct(
    ITokenRepository $token_repository,
    Request $request
  ) {
    $this->token_repository = $token_repository;
    $this->request = $request;
  }

  public function check()
  {
    return !is_null($this->user());
  }

  public function guest()
  {
    return !$this->check();
  }

  public function user()
  {
    if (!is_null($this->user)) {
      return $this->user;
    }

    $auth = $this->getAuthentication();
    if ($auth) {
      $token = $this->token_repository->getItem($auth, ['user']);
      if (!$token || !$token->user) {
        return false;
      }
      $this->user = $token->user;
    }

    return $this->user;
  }

  public function id()
  {
    if ($this->user()) {
      return $this->user()->getAuthIdentifier();
    }
  }

  public function validate(array $credentials = [])
  {
    $auth = $this->getAuthentication();
    $token = $this->token_repository->getItem($auth, ['user']);
    if (!$token || !$token->user) {
      return false;
    }

    return false;
  }

  public function setUser(Authenticatable $user)
  {
    $this->user = $user;

    return $this;
  }

  protected function getAuthentication()
  {
    return $this->request->headers->get('Authentication');
  }
}
