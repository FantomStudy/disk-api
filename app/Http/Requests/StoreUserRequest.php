<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|unique:users,email|email",
            "password" => "required|min:3|string|max:255",
        ];
    }
}
