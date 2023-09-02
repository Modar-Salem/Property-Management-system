<?php

namespace App\Http\Requests\ForgotPassword;

use App\Http\Requests\ValidationFormRequest;

class ResetPasswordRequest extends ValidationFormRequest
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
            'old_password' => 'required | string | min : 8 | max:34 ' ,
            'new_password' => 'required | string | min : 8 | max:34 '
        ];
    }
}
