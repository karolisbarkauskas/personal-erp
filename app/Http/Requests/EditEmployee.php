<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditEmployee extends FormRequest
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
            'full_name' => 'required',
            'salary_with_vat' => 'required',
            'salary_to_hands' => 'required',
            'sellable_hours_per_day' => 'required',
            'hourly_rate_sellable' => 'required',
            'employment_start' => 'required',
            'salary_to_cover' => 'numeric',
        ];
    }
}
