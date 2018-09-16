<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SaveEditSong
 * @package App\Http\Requests
 */
class SaveEditSong extends FormRequest
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
            'artist' => 'required|max:60',
            'track' => 'required|max:60',
            'link' => 'required|max:255',
            'length' => 'required|numeric|max:300'
        ];
    }
}
