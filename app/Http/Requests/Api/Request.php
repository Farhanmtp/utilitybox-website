<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class Request extends FormRequest
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
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $status = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;

        $json = [
            'success' => false,
            'status' => $status,
            'message' => __('An error occurred while validating the data.'),
            'errors' => null,
        ];

        $_errors = [];
        if (is_array($errors) && count($errors) > 0) {
            foreach ($errors as $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $_errors[] = $v;
                    }
                } else {
                    $_errors[] = $value;
                }
            }
        }

        if (!empty($_errors)) {
            $json['errors'] = $_errors;
        }

        throw new HttpResponseException(response()->json($json, $status));
    }
}
