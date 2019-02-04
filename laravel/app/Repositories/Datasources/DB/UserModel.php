<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class UserModel extends Authenticatable
{
  use SoftDeletes;

  public $incrementing = false;

  protected $table = 'users';

  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (!$this->id) {
      $this->attributes['id'] = Str::uuid()->toString();
    }
  }

  public function articles()
  {
    return $this->hasMany(ArticleModel::class, 'user_id');
  }

  public function comments()
  {
    return $this->hasMany(CommentModel::class, 'user_id');
  }
}
