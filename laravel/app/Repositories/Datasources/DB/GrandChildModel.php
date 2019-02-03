<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GrandChildModel extends Model
{
  use SoftDeletes;

  public $incrementing = false;

  protected $table = 'grandchildren';

  protected $fillable = [
    'child_id',
  ];

  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    if (!$this->id) {
      $this->attributes['id'] = Str::uuid();
    }
  }

  public function child()
  {
    return $this->belongsTo(ChildModel::class);
  }
}
