<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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

    public function messages()
    {
      return [
        'g-recaptcha-response.required' => 'Fill captcha please',
      ];
    }

    public function rules()
    {
      return [
        'name' => 'required|alpha',
        'email' => 'required|email',
        'message' => 'required|min:10',
        'g-recaptcha-response' => 'required',
      ];
    }
}
