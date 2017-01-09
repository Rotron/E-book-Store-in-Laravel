<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{

  public function listing()
  {
    return $this->belongsTo('App\Listing');
  }

}
