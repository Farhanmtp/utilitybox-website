<?php

namespace App\Models\Traits;

use App\Http\Integrations\Powwr\Requests\DocuSignRequest;
use App\Http\Integrations\Powwr\Requests\SendLoaRequest;
use App\Http\Integrations\Powwr\UdCoreApiConnector;
use App\Models\PowwrSupplier;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

trait ContractTrait
{

    /**
     * @param $deal
     * @param $documentType
     * @return array
     * @throws \ReflectionException
     * @throws \Saloon\Exceptions\InvalidResponseClassException
     * @throws \Saloon\Exceptions\PendingRequestException
     */
    public function sendDocuSign($deal, $documentType = null)
    {

        $data = $this->docuSignBody($deal);

        $connector = new UdCoreApiConnector();

        $docusign_request = new DocuSignRequest($data);

        $docusign_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($docusign_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200 && data_get($response_body, 'SendDocusignResult.EnvelopeID')) {
            $docusignResult = data_get($response_body, 'SendDocusignResult');
            $envelopeId = data_get($response_body, 'SendDocusignResult.EnvelopeID');
            if (app()->isProduction()) {
                unset($response_body['warnings']);
            }
            $responseData = [
                'success' => true,
                'envelopeId' => $envelopeId,
                'data' => $docusignResult ?: $response_body
            ];
        } else {
            $errors = $this->docuSignErrors($response_body);
            if ($result->status() == 500) {
                $errors[] = implode('<br>', array_map(function ($a, $b) {
                    return $a . ": " . (is_array($b) ? json_encode($b) : $b);
                }, array_keys($response_body), array_values($response_body)));
            }

            $responseData = ['success' => false, 'data' => $response_body, 'errors' => $errors];
        }

        if (!app()->isProduction()) {
            $responseData['api_payload'] = $docusign_request->body()->all();
        }

        return $responseData;
    }

    /**
     * @param $deal
     * @param $documentType
     * @return array
     * @throws \ReflectionException
     * @throws \Saloon\Exceptions\InvalidResponseClassException
     * @throws \Saloon\Exceptions\PendingRequestException
     */
    public function sendLoa($deal)
    {

        $data = $this->docuSignBody($deal, 'loa');

        $connector = new UdCoreApiConnector();

        $docusign_request = new SendLoaRequest($data);

        $docusign_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($docusign_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200 && data_get($response_body, 'SendLOAResult.EnvelopeID')) {
            $docusignResult = data_get($response_body, 'SendLOAResult');
            $envelopeId = data_get($response_body, 'SendLOAResult.EnvelopeID');
            if (app()->isProduction()) {
                unset($response_body['warnings']);
            }
            $responseData = [
                'success' => true,
                'envelopeId' => $envelopeId,
                'data' => $docusignResult ?: $response_body
            ];
        } else {
            $errors = $this->docuSignErrors($response_body);
            if ($result->status() == 500) {
                $errors[] = implode('<br>', array_map(function ($a, $b) {
                    return $a . ": " . (is_array($b) ? json_encode($b) : $b);
                }, array_keys($response_body), array_values($response_body)));
            }

            $responseData = ['success' => false, 'data' => $response_body, 'errors' => $errors];
        }

        if (!app()->isProduction()) {
            $responseData['api_payload'] = $docusign_request->body()->all();
        }

        return $responseData;
    }

    /**
     * @param $deal
     * @param $task
     * @return array
     */
    public function docuSignBody($deal, $document = 'contract')
    {
        if ($deal instanceof Arrayable) {
            $deal = $deal->toArray();
        }

        $companyType = data_get($deal, 'company.type');
        if (in_array(strtolower($companyType), ['ltd', 'limited'])) {
            $companyType = 'Ltd';
        }
        if (strtolower($companyType) == 'soletraders' || strtolower($companyType) == 'soletrader') {
            $companyType = 'Sole Trader';
        }
        if (strtolower($companyType) == 'plc') {
            $companyType = 'Plc';
        }
        if (strtolower($companyType) == 'llp') {
            $companyType = 'LimitedLiabilityPartnership';
        }
        data_set($deal, 'company.type', $companyType);

        $mappings = $document == 'loa' ? $this->loaDocusignMaping() : $this->docusignMaping();

        $AgentDetails = [
            "brokerage_or_aggregator_name" => 'Energyw!se Limited',    //Brokerage name
            "BrokerageName" => 'Energyw!se Limited',    //Brokerage name
            "AgentName" => 'Energyw!se Limited',    //Broker agent name
            "AgentNumber" => '020 3921 9000',    //Broker contact number
            "AgentEmail" => config('powwr.brokerage_email')    //Broker agent email
        ];


        $data = [
            "ItsAGasContract" => data_get($deal, 'utilityType') == 'gas' ? true : false,
            "ContractEmail" => data_get($deal, 'customer.email'),
            "EnvelopeID" => $document == 'loa' ? data_get($deal, 'loaEnvelopeId') : data_get($deal, 'envelopeId'),
        ];

        if ($document == 'loa') {
            $supplier = strtolower(data_get($deal, 'contract.newSupplierName'));
            $templateName = Str::startsWith($supplier, 'E-On Next') ? config('powwr.docusign_loa_template_eonnext') : config('powwr.docusign_loa_template');
            if ($templateName) {
                $data['DocusignTemplateName'] = $templateName;
            }
        } else {
            $data['ContractEmail'] = data_get($deal, 'customer.email');
            $data['TemplateOptions'] = [
                "HalfHourly" => (boolean)data_get($deal, 'smeDetails.halfHourly'),
                "ChangeOfTenancy" => false,
                "PlanType" => data_get($deal, 'quoteDetails.PlanType')
            ];
        }

        foreach ($mappings as $key => $mapping) {
            $subData = [];
            foreach ($mapping as $key1 => $key2) {
                $value = null;

                if ($key1 == 'business type') {
                    $value = 'sole trader';
                } elseif (in_array($key1, array_keys($AgentDetails))) {
                    $value = $AgentDetails[$key1];
                } else if (is_array($key2)) {
                    $values = [];
                    foreach ($key2 as $k) {
                        $val = $this->getValueForDocuSign($deal, $k, $key1);
                        if (!is_null($val) && !in_array($val, $values)) {
                            $values[] = $val;
                        }
                    }
                    $value = implode(' ', $values);
                } else if (Str::contains($key2, '|')) {
                    $keys = explode('|', $key2);
                    $values = [];
                    foreach ($keys as $k) {
                        $val = $this->getValueForDocuSign($deal, $k, $key1);
                        if (!is_null($val)) {
                            $values[] = $val;
                            break;
                        }
                    }
                    $value = array_shift($values);
                } else {
                    $value = $this->getValueForDocuSign($deal, $key2, $key1);
                }

                if (!is_null($value)) {
                    $subData[] = [
                        "Key" => $key1,
                        "Value" => $value
                    ];
                }
            }

            if (!empty($subData)) {
                $data[$key] = array_values($subData);
            }
        }

        // dd($data);
        return $data;
    }

    protected function getValueForDocuSign($deal, $key, $map_key)
    {
        $dates = [
            'customer.moveInDate',
            'customer.dateOfBirth',
            'contract.currentEndDate',
            'contract.endDate',
            'paymentDetail.directDebitDayOfMonth',
            'company.dateOfIncorporation',
            'quoteDetails.ContractEndDate',
            'contract.endDate',
        ];
        $booleans = [
            'smeDetails.IsRenewable',
            'company.isMicroBusiness',
        ];
        $suppliers = [
            'contract.currentSupplierName',
            'contract.newSupplierName',
        ];
        $value = data_get($deal, $key);

        if ($map_key == 'payment method' && $value) {
            if (in_array($value, ['MonthlyDirectDebit', 'DirectDebit', 'Direct Debit'])) {
                $value = 'Monthly Direct Debit';
            }
            if (in_array($value, ['FixedDirectDebit'])) {
                $value = 'Fixed Direct Debit';
            }
        }

        if ($key == 'company.sicCodes' && is_array($value)) {
            $value = array_shift($value);
        }
        if ($key == 'customer.landline' && !$value) {
            $value = '02039219000';
        }

        if ($key == 'contract.currentEndDate' && !$value) {
            $value = Carbon::now()->toDateString();
        }

        if ($value) {
            if (in_array($key, $dates)) {
                $value = Carbon::createFromTimestamp(strtotime($value))->utc();
            }
            if (in_array($key, $suppliers)) {
                try {
                    $supplier = PowwrSupplier::where('powwr_id', $value)->value('name');
                    if ($supplier) {
                        $value = $supplier;
                    }
                } catch (\Exception $e) {
                }
            }
        }
        if (in_array($key, $booleans)) {
            if (is_bool($value) || strlen($value)) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
        }
        if (!is_array($value) && !strlen($value)) {
            $value = null;
        }


        if ($key == 'contract.length') {
            $contractStartDate = data_get($deal, 'contract.startDate');
            $contractEndDate = data_get($deal, 'contract.endDate');

            if ($contractStartDate) {
                if ($contractEndDate) {
                    $contractStartDate = Carbon::parse($contractStartDate)->startOfMonth();
                    $contractEndDate = Carbon::parse($contractEndDate)->startOfMonth();
                    $value = $contractStartDate->diffInMonths($contractEndDate);
                } else {
                    $value = 12;
                }
            }
        }

        return $value;
    }

    public function docuSignErrors($response_body)
    {
        $errors = [];
        $_error = data_get($response_body, 'SendDocusignResult.Error') ?? data_get($response_body, 'SendLOAResult.Error');

        if (is_array($_error)) {
            $desc = data_get($_error, 'ErrorDetail');
            $msg = data_get($_error, 'Message');
            $errors[] = trim($msg, '. ') . ' - ' . $desc;
        }

        return $errors;
    }

    protected function docusignMaping()
    {
        return [
            "MainDetailsData" => [
                "premises name" => "company.name",    //Site address line 1
                "address 1" => 'site.buildingNumber',    //Site address line 1
                "address 2" => "site.buildingName",    //Site address line 2
                "address 3" => "site.postTown",    //Site address line 3
                "address 4" => "site.county",    //Site address line 4
                "post code" => "site.postcode",    //Site post code

                "ternancy start date" => "contract.startDate",    //Tenancy start date (ternancy is not a typo)
                "tenant/owner/occupier" => "",    //Tenant/owner/occupier

                "job title" => "customer.jobTitle",    //Job title
                "email" => "customer.email",    //Email address
                "landline" => "customer.landline",    //Landline telephone number
                "mobile" => "customer.mobile",    //Mobile phone number
                "date of birth" => "customer.dateOfBirth",    //Date of birth

                "billing address1" => "billingAddress.buildingNumber",    //Billing address line 1
                "billing address2" => "billingAddress.buildingName",    //Billing address line 2
                "billing address3" => "billingAddress.postTown",    //Billing address line 3
                "billing address4" => "billingAddress.county",    //Billing address line 3
                "billing postcode" => "billingAddress.postcode",    //Billing address line 4

                "brief notes" => "",    //Brief notes
                "director dob" => "customer.dateOfBirth",    //Director date of birth

                "bank name" => "bankDetails.name",    //Bank/Building Society name
                "bank account name" => "bankDetails.accountName",    //Bank account name
                "account number" => "bankDetails.accountNumber",    //Bank account number
                "sort code" => "bankDetails.sortCode",    //Bank sort code
                "bank post code" => "bankAddress.postcode",    //Bank post code

                "pub company" => "company.name",    //Pub company
                "company name" => "company.name",    //Pub company
                "business type" => "company.type",    //Business type
                "business start date" => "company.dateOfIncorporation",    //Business start date
                "company number" => "company.number",    //Company/Business number
                "company address 1" => "company.buildingNumber",    //Company address line 1
                "company address 2" => "company.buildingName",    //Company address line 2
                "company address 3" => "company.postTown",    //Company address line 3
                "company address 4" => "company.county",    //Company address line 4
                "company post code" => "company.postcode",    //Company post code

                "contact name" => ["customer.firstName", "customer.lastName"],    //Contact name
                "director address1" => "customer.buildingNumber",    //Director address line 1
                "director address2" => "customer.buildingName",    //Director address line 2
                "director address3" => "customer.postTown",    //Director address line 2
                "director postcode" => "customer.postcode",    //Director post code
                "director forename" => "customer.firstName",    //Director forename
                "director surname" => "customer.lastName",    //Director surname

                "previous address1" => "",    //Previous address line 1
                "previous address2" => "",    //Previous address line 2
                "previous address3" => "",    //Previous address line 3
                "previous postcode" => "",    //Previous post code

                "mpr 1" => "smeDetails.meterNumber",    //Unique gas Meter Point Reference Number (MPRN)
                "mpan top line 1" => "smeDetails.mpanTop",    //Top line of unique electricity Meter Point Administration Number (MPAN)
                "mpan bottom line 1" => "smeDetails.meterNumber",    //Bottom line of unique electricity Meter Point Administration Number (MPAN)

                "gas contract end date" => "contract.currentEndDate",    //End date of current gas contract
                "elec contract end date" => "contract.currentEndDate",    //End date of current electricity contract
                "current gas supplier 1" => "contract.currentSupplierName|contract.currentSupplier",    //Current gas supplier
                "current electric supplier 1" => "contract.currentSupplierName|contract.currentSupplier",    //Current electricity supplier
                "new supplier gas" => "quoteDetails.Supplier|contract.newSupplierName",    //New gas supplier
                "new supplier electricity" => "quoteDetails.Supplier|contract.newSupplierName",    //New electricity supplier
                "gas new contract length" => "quoteDetails.Term|contract.length",    //Length of new gas contract (months)
                "elec new contract length" => "quoteDetails.Term|contract.length",    //Length of new electricity contract (months)
                "payment method" => "quoteDetails.PaymentMethod.DisplayName|paymentDetail.method",    //Payment method

                "kva" => "usage.unit",    //KVA
                "dd start date" => "paymentDetail.directDebitDayOfMonth",    //DD start date
            ],
            //UsageRatesData
            "UsageRatesData" => [
                "daycharge" => "quoteDetails.DayUnitrate",    //Day unit rate (p/kWh)
                "nightcharge" => "quoteDetails.NightUnitrate",    //Night unit rate (p/kWh)
                "eveandwendcharge" => "quoteDetails.WendUnitrate",    //Everning/weekend unit rate (p/kWh)
                "standing charge" => "quoteDetails.StandingCharge",    //Standing charge (p/day)
                "dayusage" => "usage.day|usage.unit",    //Day usage (kWh)
                "nightusage" => "usage.night",    //Night usage (kWh)
                "eveandwendusage" => "usage.weekend",    //Evening/weekend usage (kWh)
            ],
            //MeterDetailsData
            "MeterDetailsData" => [
                "ref" => "",    //Supplier product code/tariff code
                "fits" => "quoteDetails.Fits",    //FiTs value
                "sc" => "",    //Standing charge calculated per billing period
                "period" => "",    //Billing period (quarterly/monthly)
            ],
            //AuxiliaryDetailsData
            "AuxiliaryDetailsData" => [
                "siteorcompanyname" => 'site.name|company.name',    //Site address line 1
                "siteorcompanyaddress1" => 'site.buildingNumber',    //Site address line 1
                "siteorcompanyaddress2" => "site.buildingName",    //Site address line 2
                "siteorcompanyaddress3" => "site.postTown",    //Site address line 3
                "siteorcompanyaddress4" => "site.county",    //Site address line 4

                "mpan" => "smeDetails.meterNumber",    //Bottom line of unique electricity Meter Point Administration Number (MPAN)
                "mprn" => "smeDetails.meterNumber",    //Bottom line of unique electricity Meter Point Administration Number (MPAN)
                "accountno" => "bankDetails.accountNumber",    //Bottom line of unique electricity Meter Point Administration Number (MPAN)


                "ProductName" => "quoteDetails.PlanType|quoteDetails.Pricebook",
                "AMRElec" => "",
                "RenewableElec" => "smeDetails.IsRenewable",
                "StandardProductElec" => "quoteDetails.PlanType|quoteDetails.Pricebook",
                "AMRGas" => "",
                "RenewableGas" => "smeDetails.IsRenewable",
                "StandardProductGas" => "quoteDetails.PlanType|quoteDetails.Pricebook",

                "CompanySicCode" => "company.sicCodes",    //Company SIC code

                "MpanSerialNumber" => "smeDetails.meterSerialNumber",    //Electricity meter serial number
                "MprnSerialNumber" => "smeDetails.meterSerialNumber",    //Gas meter serial number
                "Kva" => "",    //kVA
                "CapacityCharge" => "",    //Capacity Charge (p/day/kVA)

                "ContactTitle" => "customer.title",    //Main contact name title (e.g. Mrs)
                "ContactEmail" => "customer.email",


                "billing_title" => "customer.title",    //Main contact name title (e.g. Mrs)
                "BillingLandline" => "customer.landline",    //Billing landline
                "BillingMobile" => "customer.mobile",    //Billing landline
                "BillingEmail" => "customer.email",    //Billing contact email
                "BillingContactFirstName" => "customer.firstName",    //Billing contact first name
                "BillingContactLastName" => "customer.lastName",    //Billing contact surname

                "billing_address_landline" => "customer.landline",
                "billing_address_mobile" => "customer.mobile",

                "MicroBusiness" => "company.isMicroBusiness",    //Whether the customer qualifies as a micro-business (true/false)
                "YearsAtAddress" => "years_at_address",    //Number of years at the current address

                "ElectricityUnitRateUplift" => "quoteDetails.Uplift",
                "GasUnitRateUplift" => "quoteDetails.Uplift",
                "ElectricityStandingChargeUplift" => "quoteDetails.StandingChargeUplift",
                "GasStandingChargeUplift" => "quoteDetails.StandingChargeUplift",
                "ElectricityAnnualCommission" => "quoteDetails.RawCommission",    //Electricity Annual Commission in £
                "GasAnnualCommission" => "quoteDetails.RawCommission",    //Gas Annual Commission in £

                "ElectricityAnnualPriceInclusive" => "quoteDetails.RawAnnualPriceInclusive",
                "GasAnnualPriceInclusive" => "quoteDetails.RawAnnualPriceInclusive",

                "email" => "customer.email",    //Email address
                "landline" => "customer.landline",    //Landline telephone number
                "mobile" => "customer.mobile",    //Mobile phone number
                "date of birth" => "customer.dateOfBirth",    //Date of birth

                "pub company" => "company.name",    //Pub company
                "business type" => "company.type",    //Business type
                "company number" => "company.number",    //Company/Business number
                "CompanyEmail" => "customer.email",
                "supplyemail" => "customer.email",

                "brokerage_or_aggregator_name" => "",    //Brokerage name
                "BrokerageName" => "",    //Brokerage name
                "AgentName" => "",    //Broker agent name
                "AgentNumber" => "",    //Broker contact number
                "AgentEmail" => "",    //Broker agent email
                "ElectricityNewContractEndDate" => "quoteDetails.ContractEndDate|contract.endDate",    //End date of new electricity contract
                "GasNewContractEndDate" => "quoteDetails.ContractEndDate|contract.endDate",    //End date of new gas contract
            ],
            //TemplateOptions
            /*"TemplateOptions" => [
                "PlanType" => "quoteDetails.Pricebook",    //Plan Type (fixed|green|renewable) For multiple plan types include '|' between each plan
                "Change of Tenancy" => "",    //Change of tenancy
                "HalfHourly" => "",    //Half hourly meter
            ]*/
        ];
    }

    protected function loaDocusignMaping()
    {
        return [
            "MainDetailsData" => [
                "premises name" => "company.name",
                "email" => "customer.email",
                "contact name" => ["customer.firstName", "customer.lastName"],
                "post code" => "site.postcode",
                "job title" => "customer.jobTitle",
                "business type" => "company.type",
            ],
            "AuxiliaryDetailsData" => [
                "siteorcompanyname" => 'company.name',    //Site address line 1
                "siteorcompanyaddress1" => 'site.buildingNumber',    //Site address line 1
                "siteorcompanyaddress2" => "site.buildingName",    //Site address line 2
                "siteorcompanyaddress3" => "site.postTown",    //Site address line 3
                "siteorcompanyaddress4" => "site.county",    //Site address line 4
                "siteorcompanypostcode" => "site.postcode",    //Site address line 4

                "mpan" => "smeDetails.meterNumber",
                "mprn" => "smeDetails.meterNumber",
                "accountno" => "bankDetails.accountNumber",
                "siteorcompanyemail" => "customer.email",
                "siteorcompanybusinessnumber" => "company.number",
                "siteorcompanylandline" => "customer.landline",
                "drldate" => "",
            ],
        ];
    }

}
