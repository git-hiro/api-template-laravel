<?php

namespace App\Repositories\Datasources\DB;

use Illuminate\Database\Eloquent\Model;

class TokenModel extends Model
{
  public $incrementing = false;

  protected $table = 'tokens';

  protected $fillable = [
  ];

  protected $dates = ['created_at', 'updated_at'];

  public function user()
  {
    return $this->belongsTo(UserModel::class);
  }
}
