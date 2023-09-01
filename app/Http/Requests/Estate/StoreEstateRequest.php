<?php

namespace App\Http\Requests\Estate;

use App\Http\Requests\ValidationFormRequest;

class StoreEstateRequest extends ValidationFormRequest
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
            'operation_type' => 'required',
            'governorate'=>'required',
            'description'=>'required',
            'price'=>'required',
            'space' => 'required' ,
            'estate_type' => 'required' ,
            'image' => 'mimes:jpeg,jpg,png,gif ',
            'image1' => 'mimes:jpeg,jpg,png,gif' ,
            'image2' => 'mimes:jpeg,jpg,png,gif'  ,
            'image3' => 'mimes:jpeg,jpg,png,gif' ,
            'image4' => 'mimes:jpeg,jpg,png,gif' ,
            'image5' => 'mimes:jpeg,jpg,png,gif' ,
            'image6' => 'mimes:jpeg,jpg,png,gif' ,
            'image7' => 'mimes:jpeg,jpg,png,gif' ,
            'image8' => 'mimes:jpeg,jpg,png,gif' ,
            'image9' => 'mimes:jpeg,jpg,png,gif'
        ];
    }
}
