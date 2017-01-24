<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  // Order belongs to a particular listing..
  public function listing()
  {
    return $this->belongsTo('App\Listing');
  }

  // Order made by particular user

  // Order can have multiple sales..
  public function orderSales()
  {
    return $this->hasMany('OrderSale');
  }
}
