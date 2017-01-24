<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\R
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  public function orders()
  {
    return $this->hasMany('App\Order');
  }

  public function setPasswordAttribute($password)
  {
    if (!is_null($password)) {
      $this->attributes['password'] = $password;
    }
  }

  // Insert new confirmation code if checkbox value is 'no'
  public function setConfirmationCode()
  {
    if ($request->input('confirmed') == 'yes') {
      $user->confirmation_code = null;
    } else {
      $user->confirmation_code = str_random(32);
    }
  }

}
