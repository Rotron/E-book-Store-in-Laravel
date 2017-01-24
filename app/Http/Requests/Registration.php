<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Registration extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
      return [
        'username' => 'required|unique:users,username|alpha_num',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:10',
        'g-recaptcha-response' => 'required',
      ];
    }
}
