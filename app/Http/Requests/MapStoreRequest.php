<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MapStoreRequest extends FormRequest
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
            'inner_collab' => 'required',
            'onesoft_collab' => 'required_without:prestapro_collab',
            'prestapro_collab' => 'required_without:onesoft_collab',
        ];
    }
}
