<?php

namespace App\Http\Requests\SearchFilter;

use App\Http\Requests\ValidationFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SearchRequest extends ValidationFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required | in:estate,car' ,
            'description' => 'required|max : 255'
        ];
    }

}
