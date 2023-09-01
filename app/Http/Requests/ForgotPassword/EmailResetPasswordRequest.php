<?php

namespace App\Http\Requests\ForgotPassword;


use App\Http\Requests\ValidationFormRequest;

class EmailResetPasswordRequest extends ValidationFormRequest
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
            'code' => 'required|string|exists:reset_code_passwords,code',
            'password' => 'required | confirmed'
        ];
    }
}
