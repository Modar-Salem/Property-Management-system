<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\ValidationFormRequest;

class LikesNumberRequest extends ValidationFormRequest
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
            'type' => 'Required |  in:estate,car,all'
        ];
    }
}
