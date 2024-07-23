<?php

namespace App\Http\Requests\Api;

class PowwrOffers extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'utilityType' => ['required', 'in:gas,electric'],
            'meterNumber' => ['required', 'string'],
            'currentSupplierName' => ['nullable', 'string'],
            'curentSupplierName' => ['nullable', 'string'],

            'contractRenewalDate' => ['nullable', 'date'],
            'contractEndDate' => ['nullable', 'date'],
            'newContractEndDate' => ['nullable', 'date'],
            'renewal' => ['nullable', 'boolean'],
            'cot' => ['nullable', 'boolean'],
            'outOfContract' => ['nullable', 'boolean'],
            'smartMeter' => ['nullable', 'boolean'],
            'sortByCommission' => ['nullable', 'boolean'],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'utilityType.required' => "Utility type is required",
            'meterNumber.required' => "Meter number is required",
            'currentSupplierName.required' => "Current supplier is required",
        ];
    }
}
