<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class UpdateDeal extends Request
{

    public function getValidatorInstance()
    {
        if ($this->request->has('meterNumber')) {
            $this->merge([
                'smeDetails' => array_merge($this->input('smeDetails', []), [
                    'meterNumber' => $this->input('meterNumber')
                ])
            ]);
        }

        if ($this->request->has('utilityType')) {
            $this->merge([
                'smeDetails' => array_merge($this->input('smeDetails',[]), [
                    'utilityType' => $this->input('utilityType')
                ])
            ]);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $utilityType = $this->input('utilityType');

        $rules = [
            //'userId' => ['required', 'string'],
            'dealId' => ['required', 'string'],
            'supplierId' => ['required', 'string'],

            'utilityType' => ['nullable', 'in:gas,electric'],
            'meterNumber' => ['nullable', 'string'],

            'customer' => ['nullable', 'array'],
            'customerCompany' => ['nullable', 'array'],
            'site' => ['nullable', 'array'],
            'site.name' => ['required', 'string'],

            'contract' => ['nullable', 'array'],
            'contract.currentEndDate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'contract.startDate' => ['required', 'date', 'date_format:Y-m-d'],
            'contract.endDate' => ['required', 'date', 'date_format:Y-m-d'],

            'smeDetails.meterNumber' => [Rule::requiredIf(function () {
                return !\request()->input('meterNumber');
            }),],
            'smeDetails.utilityType' => [Rule::requiredIf(function () {
                return !\request()->input('utilityType');
            }), 'in:gas,electric'],

            'smeDetails.usage' => ['nullable', 'array'],
            'smeDetails.rates' => ['nullable', 'array'],
            'smeDetails.lastReading' => ['nullable', 'array'],
            'smeDetails.isRenewable' => ['nullable', 'boolean'],
            'smeDetails.standingChargeType' => ['nullable', 'string', 'in:None,Low,Standard,High'],
        ];

        if ($utilityType == 'gas') {
            $rules['meterNumber'][] = 'min:6';
            $rules['meterNumber'][] = 'max:10';
            $rules['smeDetails.meterNumber'][] = 'min:6';
            $rules['smeDetails.meterNumber'][] = 'max:10';
        } else {
            $rules['meterNumber'][] = 'min:13';
            $rules['meterNumber'][] = 'max:13';
            $rules['smeDetails.meterNumber'][] = 'min:13';
            $rules['smeDetails.meterNumber'][] = 'max:13';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'utilityType.required' => "Utility type is required",
            'meterNumber.required' => "Meter number is required",
            'supplierId.required' => "Supplier is required",
            'site.name.required' => "Site name is required.",
            'contract.startDate.required' => "Contract start date is required.",
            'contract.endDate.required' => "Contract start date is required.",
            'smeDetails.meterNumber.required' => "Meter number is required",
            'smeDetails.utilityType.required' => "Utility type is required",
        ];
    }
}
