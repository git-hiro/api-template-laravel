<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChildModel extends Model
{
  use SoftDeletes;

  public $incrementing = false;

  protected $table = 'children';

  protected $fillable = [
    'user_id',
  ];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  public function user()
  {
    return $this->belongsTo(UserModel::class);
  }

  public function comments()
  {
    return $this->hasMany(CommentModel::class, 'article_id');
  }
}
