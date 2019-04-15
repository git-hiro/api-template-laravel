<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentModel extends Model
{
  use SoftDeletes;

  public $incrementing = false;

  protected $table = 'comments';

  protected $fillable = [
    'user_id',
    'article_id',
    'content',
  ];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  public function user()
  {
    return $this->belongsTo(UserModel::class);
  }

  public function article()
  {
    return $this->belongsTo(ArticleModel::class);
  }
}
