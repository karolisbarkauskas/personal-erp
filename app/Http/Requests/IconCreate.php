<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IconCreate extends FormRequest
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
            'name' => 'required',
            'file' => 'required|mimetypes:image/png,image/svg,image/svg+xml',
        ];
    }
}
