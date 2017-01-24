<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class Login extends FormRequest
{

  public function authorize()
  {
    return true;
  }

    public function rules()
    {
      return [
        'username' => 'required',
        'password' => 'required',
      ];
    }
}
