<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

  protected $fillable = ['product_name', 'price', 'product_description'];

  public function sales()
  {
    return $this->hasMany(Sale::class);
  }

}
