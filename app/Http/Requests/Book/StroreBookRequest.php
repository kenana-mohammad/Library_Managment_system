<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StroreBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' =>'required | string ',
            'description' =>'nullable | string | max:400',
            'img' =>'nullable | mimes:jpg,bmp,png',
            'quantity'=>'required |string',
            'genre' =>'nullable|string',
            'price' => 'nullable '
        ];
    }
}
