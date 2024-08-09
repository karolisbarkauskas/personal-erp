<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecurringExpenses extends FormRequest
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
            'priority' => 'required',
            'name' => 'required',
            'size' => 'required|numeric',
            'category' => 'required',
            'installment' => 'numeric',
            'payment_date' => 'numeric',
            'expense_payment_name' => 'nullable',
        ];
    }
}
