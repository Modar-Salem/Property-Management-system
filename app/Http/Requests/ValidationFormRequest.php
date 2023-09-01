<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ValidationFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validate)
    {
        throw new ValidationException($validate,
            response()->json([
                'Status' => false,
                'Validation Error' => $validate->errors()
            ])
        );
    }
}
