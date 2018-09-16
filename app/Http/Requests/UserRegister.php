<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserRegister
 * @package App\Http\Requests
 */
class UserRegister extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:60|unique:users',
            'name' => 'required|string|max:60',
            'password'=> 'required|min:6|max:100|confirmed',
            'role' => 'integer'
        ];
    }
}
