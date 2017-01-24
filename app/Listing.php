<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{

  // Can be ordered multiple times, has many orders..
  public function orders()
  {
    return $this->hasMany('App\Order');
  }

}
