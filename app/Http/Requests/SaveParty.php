<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SaveParty
 * @package App\Http\Requests
 */
class SaveParty extends FormRequest
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
            'name' => 'required|string|max:60',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'tags' => 'required|max:128',
            'capacity' => 'required|integer',
            'length' => 'required|numeric|max:50',
            'image' => 'required|max:1024'
        ];
    }
}
