<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

  public function articles()
  {
    return $this->hasMany(ArticleModel::class, 'user_id');
  }

  public function comments()
  {
    return $this->hasMany(CommentModel::class, 'user_id');
  }
}
