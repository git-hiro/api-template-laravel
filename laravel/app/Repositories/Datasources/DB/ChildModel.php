<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ChildModel extends Model
{
  use SoftDeletes;

  public $incrementing = false;

  protected $table = 'children';

  protected $fillable = [
    'user_id',
  ];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (!$this->id) {
      $this->attributes['id'] = Str::uuid();
    }
  }

  public function user()
  {
    return $this->belongsTo(UserModel::class);
  }

  public function grandchildren()
  {
    return $this->hasMany(GrandChildModel::class, 'user_id');
  }
}
