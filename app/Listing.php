<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
  use SoftDeletes;
  
  protected $dates = ['deleted_at'];

  // Can be ordered multiple times, has many orders..
  public function orders()
  {
    return $this->hasMany('App\Order');
  }

}
