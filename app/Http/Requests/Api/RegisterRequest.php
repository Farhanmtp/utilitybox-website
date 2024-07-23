<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'first_name' => ['required', 'string', 'min:5'],
            'last_name' => ['nullable', 'string', 'min:5'],
            'gender' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date', 'date_format:Y-m-d'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'address2' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'country_code' => ['nullable', 'string'],
            'zipcode' => ['nullable', 'string'],
            'avatar' => ['nullable', File::image()->max('1mb')],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ];
    }
}
