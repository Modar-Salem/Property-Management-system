<?php

namespace App\Http\Requests\Image;

use App\Http\Requests\ValidationFormRequest;


class ImageRequest extends ValidationFormRequest
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
            'image' => 'Required | mimes:jpeg,jpg,png'
        ];
    }
}
