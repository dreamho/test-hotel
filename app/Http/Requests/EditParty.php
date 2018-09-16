<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditParty extends FormRequest
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
            'description' => 'required|string|max:255',
            'tags' => 'required|max:128',
            'image' => 'image|max:1024'
        ];
    }
}
