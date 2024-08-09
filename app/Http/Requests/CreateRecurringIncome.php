<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRecurringIncome extends FormRequest
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
            'service_id' => ['required'],
            'client_id' => ['required'],
            'date' => ['required'],
            'amount' => ['required'],
            'vat_size' => ['required'],
            'service_line' => ['required'],
            'period' => ['required'],
            'expense_id' => ['nullable'],
        ];
    }
}
