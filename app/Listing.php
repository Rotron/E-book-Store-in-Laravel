<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
  public function transaction()
  {
    $this->hasMany('App\Transaction');
  }
}
