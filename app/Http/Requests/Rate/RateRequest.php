<?php

namespace App\Http\Requests\Rate;
use App\Http\Requests\ValidationFormRequest;

class RateRequest extends ValidationFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'rate' => 'Required | min :1 | max :5 |integer ',
            'type' => 'required | in:car,estate'
        ];
    }
}
