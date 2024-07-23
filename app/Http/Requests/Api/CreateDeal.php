<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class CreateDeal extends Request
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
                'smeDetails' => array_merge($this->input('smeDetails', []), [
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
        //dd($this->all());
        $rules = [
            //'userId' => ['required'],
            'utilityType' => ['nullable', 'in:gas,electric'],
            'meterNumber' => ['nullable', 'string'],
            'supplierId' => ['required', 'string'],

            'customer' => ['nullable', 'array'],
            'customerCompany' => ['nullable', 'array'],
            'site' => ['required', 'array'],
            //'site.name' => ['required', 'string'],

            'contract' => ['nullable', 'array'],
            'contract.currentEndDate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'contract.startDate' => ['required', 'date', 'date_format:Y-m-d'],
            //'contract.endDate' => ['required', 'date', 'date_format:Y-m-d'],

            /*'smeDetails.meterNumber' => [Rule::requiredIf(function () {
                return !\request()->input('meterNumber');
            })],*/
            /*'smeDetails.utilityType' => [Rule::requiredIf(function () {
                return !\request()->input('utilityType');
            }), 'in:gas,electric'],*/
            //'smeDetails.usage' => ['nullable', 'array'],
            //'smeDetails.rates' => ['nullable', 'array'],
        ];
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
            'contract.endDate.required' => "Contract end date is required.",
            'smeDetails.meterNumber.required' => "Meter number is required in sme details",
            'smeDetails.utilityType.required' => "Utility type is required in sme details",
        ];
    }
}
