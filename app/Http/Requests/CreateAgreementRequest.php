<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAgreementRequest extends FormRequest
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
            'name' => 'string|nullable',
            'description' => 'string|nullable',
            'agreement_group_id' => 'required|numeric',
            'task_id' => 'required|nullable',
            'from' => 'date_format:Y-m-d',
            'to' => 'date_format:Y-m-d|after:from',
            'budget' => 'numeric|min:0|nullable',
            'estimate' => 'numeric|min:0|nullable',
        ];
    }

    public function messages()
    {
        return [
            'from.date_format' => 'Invalid agreement FROM date format: Y-m-d',
            'to.date_format' => 'Invalid agreement TO date format: Y-m-d',
            'to.after' => 'End date is before Start date.',
        ];
    }
}
