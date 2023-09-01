<?php

namespace App\Http\Requests\Register;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SignUpRequest extends FormRequest
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
            'name' => 'required|string|max:34',

            'email' => 'required|string|email|max:255|unique:users',

            'password' => 'required|string|min:8|max:34',

            'phone_number' => 'required|string|min:10|max:20',

            'facebook_URL' => 'nullable|url',

            'instagram_URL' => 'nullable|url',

            'twitter_URL' => 'nullable|url',
        ];
    }
    protected function failedValidation(Validator $validate)
    {
        $error = $validate->errors() ;
        if($error->has('email') && $error->get('email')[0] === "The email has already been taken.")
        {
            $user_temp = User::where('email' , $this['email'])->first() ;
            if($user_temp->email_verified_at == null)
            {
                throw new ValidationException(
                    $validate,
                    response()->json([
                        'Status' => false,
                        'Validation Error' => 'email must be verified'
                    ])
                );
            }
        }
        throw new ValidationException($validate,
            response()->json([
                'Status' => false,
                'Validation Error' => $validate->errors()
            ])
        );
        // If your custom logic doesn't throw an exception, Laravel's default behavior will apply.
    }
}
