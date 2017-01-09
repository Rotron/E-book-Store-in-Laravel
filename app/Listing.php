<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
  public function sales()
  {
    $this->hasMany('App\Sale');
  }
}
