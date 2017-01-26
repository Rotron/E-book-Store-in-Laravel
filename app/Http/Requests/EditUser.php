<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EditUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
      // $username = $request->route('user')->username;
      $userId = $request->route('user')->id;

        return [
          'username' => "required|unique:users,username,$userId,id",
          'email'   => "required|unique:users,email,$userId,id",
          'confirmed' => 'in:yes,no'
        ];
    }
}
