<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSale extends Model
{
  // 
  public function order()
  {
    $this->belongsToMany('App\Order');
  }
}
