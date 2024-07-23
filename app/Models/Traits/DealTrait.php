<?php

namespace App\Models\Traits;

use App\Http\Integrations\Powwr\ConnectApi;
use App\Http\Integrations\Powwr\Requests\CreateDealRequest;
use App\Http\Integrations\Powwr\Requests\SubmitQuoteRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

trait DealTrait
{

    protected array $contact_mapping = [
        "title" => "title",
        "firstName" => "firstName",
        "lastName" => "lastName",
        "dateOfBirth" => "dateOfBirth",
        //"isACompanyOwner" => 'isACompanyOwner',
        "email" => "email",
        "phoneNumbers" => ['mobile', 'landline'],
        "jobTitle" => "jobTitle",
    ];

    protected array $address_mapping = [
        'organisationName',
        'departmentName',
        'subBuildingName',
        'buildingName',
        'buildingNumber',
        'dependentThoroughfareName',
        'thoroughfareName',
        'doubleDependentLocality',
        'dependentLocality',
        'postTown',
        'county',
        'postcode',
        'poBox'
    ];

    protected array $contract_details_mapping = [
        "isRenewalForSupplier" => 'boolean',
        "isRenewalForBrokerage" => 'boolean',
        "isNewConnection" => 'boolean',
        "isOutOfContract" => 'boolean',
        "isFieldSale" => 'boolean',
        "currentSupplier" => 'array',
        "newSupplier" => 'array',
        "hasTerminationNoticeBeenServedWithCurrentSupplier" => 'boolean',
        "anyOutstandingDebtWithCurrentSupplier" => 'boolean',
        "fulfilmentCode" => "string",
        "pricebook" => "string",
        "product" => "string",
        "commercialUsagePercentage" => 'number',
        "brokeragePaymentTerm" => "string",
        "currentContractEndDate" => "date",
        "newContractStartDate" => "date",
        "newContractEndDate" => "date",
        "contractSignedDate" => "date"
    ];

    public function saveDeal()
    {
        $deal = $this;


        $request_data = $this->getBody($deal, 'update');

        $connector = new ConnectApi();

        $deal_request = new CreateDealRequest($request_data);

        $deal_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $apiResponse = $connector->send($deal_request);

        $apiResponseBody = $apiResponse->body();
        if (isJson($apiResponseBody)) {
            $apiResponseBody = json_decode($apiResponseBody, true);
        }

        if ($apiResponse->status() == 200) {
            $dealID = data_get($apiResponseBody, 'dealId');
            if ($dealID != $deal->dealId) {
                $deal->dealId = $dealID;
                $deal->save();
            }
            $responseData = [
                'success' => true,
                'data' => $apiResponseBody,
                'dealId' => $dealID,
            ];
        } else {
            $errors = $this->PowwrErrors($apiResponseBody);
            if ($apiResponse->status() == 500) {
                $errors[] = implode('<br>', array_map(function ($a, $b) {
                    return $a . ": " . (is_array($b) ? json_encode($b) : $b);
                }, array_keys($apiResponseBody), array_values($apiResponseBody)));
            }
            $responseData = [
                'status' => $apiResponse->status(),
                'success' => false,
                'data' => $apiResponseBody,
                'errors' => $errors
            ];
        }

        if (app()->isProduction()) {
            unset($apiResponseBody['warnings']);
        } else {
            $responseData['api_payload'] = $deal_request->body()->all();
        }

        return $responseData;
    }

    public function sendQuote($deal, $documentType = null)
    {
        $connector = new ConnectApi();

        $quote_request = new SubmitQuoteRequest([
            'dealId' => $deal->dealId,
            'utilityType' => ($deal->utilityType == 'gas' ? 'SmeGas' : 'SmeElectricity'),
            'quotationType' => ($deal->utilityType == 'matrix' ? 'Matrix ' : 'Custom'),
        ]);

        $quote_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($quote_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200) {

            $responseData = [
                'success' => true,
                'data' => $response_body,
                'message' => 'Deal updated successfully.'
            ];
        } else {

            $responseData = [
                'success' => false,
                'data' => $response_body,
                'errors' => $this->PowwrErrors($response_body)
            ];
        }

        if (app()->isProduction()) {
            unset($response_body['warnings']);
        } else {
            $responseData['api_payload'] = $quote_request->body()->all();
        }

        return $responseData;
    }


    /**
     * @param $deal
     * @param $task
     * @return array
     */
    public function getBody($deal, $task = 'create'): array
    {

        if ($deal instanceof Arrayable) {
            $deal = $deal->toArray();
        }

        $companyType = data_get($deal, 'company.type');
        if (strtolower($companyType) == 'ltd') {
            $companyType = 'Limited';
        }
        if (strtolower($companyType) == 'plc') {
            $companyType = 'PLC';
        }
        if (strtolower($companyType) == 'llp') {
            $companyType = 'LimitedLiabilityPartnership';
        }
        data_set($deal, 'company.type', $companyType);

        $brokerageId = config('powwr.brokerage_id');
        $brokerageEmail = config('powwr.brokerage_email');

        $utilityType = data_get($deal, 'utilityType');


        $deal_data = [
            "brokerage" => [
                "powwrId" => $brokerageId
            ],
            "strictValidation" => false,
            //"statusWebhookUrl" => 'false',
            "aggregatorBrokerage" => [
                "powwrId" => $brokerageId
            ],
            "agent" => [
                "powwrId" => $brokerageEmail
            ],
        ];

        if ($dealId = data_get($deal, 'dealId')) {
            $deal_data['dealId'] = $dealId;
        }

        $customerContact = $this->getCustomerContact($deal);
        if ($task == 'create') {
            $customerContact["consent"] = [];
        }

        if (!empty($customerContact)) {
            $deal_data['customer']['contact'] = $customerContact;
        }

        $company = $this->getCompany($deal);
        if (!empty($company)) {
            if (!isset($company['address'])) {
                $company['address'] = [];
            }
            $deal_data['customer']['company'] = $company;
        }

        $deal_data['customer']['referenceCodes'] = [
            [
                "value" => $brokerageId,
                "ownerType" => "Brokerage"
            ]
        ];

        $site = [
            'name' => data_get($deal, 'site.name') ?? 'My Site',
            "contact" => [
                "consent" => []
            ],
            "siteAddress" => (object)[],
        ];

        if (!empty($customerContact)) {
            $site['contact'] = $customerContact;
        }

        $contractDetails = $this->getContractDetails($deal);
        $siteAddress = $this->getSiteAddress($deal);
        $billingDetails = $this->getBillingDetails($deal);


        if (!empty($siteAddress)) {
            if (!empty($billingDetails) && empty($billingDetails['address'])) {
                $billingDetails['address'] = $siteAddress;
            }
            $site['siteAddress'] = $siteAddress;
        }

        $utility = [];
        if ($utilityType == 'gas') {
            $utility['smeGasDetails'] = $this->getSmeDetails($deal);
        } else {
            $utility['smeElectricDetails'] = $this->getSmeDetails($deal);
        }

        if (!empty($contractDetails)) {
            $utility['contractDetails'] = $contractDetails;
        }

        if (!empty($billingDetails)) {
            $utility['billingDetails'] = $billingDetails;
        }

        if (!empty($utility)) {
            $site['utilities'][] = $utility;
        }

        $deal_data['sites'][] = $site;

        $deal_data = array_filter_recursive($deal_data, function ($a) {
            return !is_null($a);
        });

        return $deal_data;
    }


    /**
     * @param $deal
     * @param $task
     * @return array
     */

    protected function getBillingDetails($deal)
    {

        $details = [];

        $billingAddress = $this->getBillingAddress($deal);
        $customerContact = $this->getCustomerContact($deal);
        $bankDetails = $this->getBankDetails($deal);

        if (!empty($billingAddress)) {
            $details['address'] = $billingAddress;
        }

        if ($paymentMethod = data_get($deal, 'paymentDetail.method')) {
            if (in_array($paymentMethod, ['MonthlyDirectDebit', 'DirectDebit', 'Direct Debit'])) {
                $paymentMethod = 'Monthly Direct Debit';
            }
            if (in_array($paymentMethod, ['FixedDirectDebit'])) {
                $paymentMethod = 'Fixed Direct Debit';
            }

            $details['payment']['method'] = $paymentMethod;
        }

        if ($directDebitDayOfMonth = data_get($deal, 'paymentDetail.directDebitDayOfMonth')) {
            $details['payment']['directDebitDayOfMonth'] = (int)$directDebitDayOfMonth;
        }

        if (!empty($bankDetails)) {
            $details['payment']['bankDetails'] = $bankDetails;
        }

        if (!empty($details)) {
            if (!empty($customerContact)) {
                $details['contact'] = $customerContact;
            }
            //$details['deliveryMethod'] = 'Email';
            $details['frequency'] = 'Monthly';
        }

        return $details;
    }

    protected function getContractDetails($deal)
    {
        $details = [];

        $newSupplier = data_get($deal, 'contract.newSupplier');

        if ($newSupplier) {
            foreach ($this->contract_details_mapping as $key => $type) {
                if (data_get($deal, 'contractDetails.' . $key)) {
                    $details[$key] = data_get($deal, 'contractDetails.' . $key);
                }
            }

            if ($currentSupplier = data_get($deal, 'contract.currentSupplier')) {
                $details['currentSupplier']['powwrId'] = $currentSupplier;
            } else if ($supplierId = data_get($deal, 'supplierId')) {
                $details['currentSupplier']['powwrId'] = $supplierId;
            }

            if ($newSupplier) {
                $details['newSupplier']['powwrId'] = $newSupplier;
            }

            $details['isOutOfContract'] = true;
            if ($contractCurrentEndDate = data_get($deal, 'contract.currentEndDate')) {
                $details['currentContractEndDate'] = formatDate($contractCurrentEndDate);
                if (strtotime($contractCurrentEndDate) > time()) {
                    $details['isOutOfContract'] = false;
                }
            } else {
                $details['currentContractEndDate'] = date('Y-m-d');
            }

            if ($isRenewalForSupplier = data_get($deal, 'contract.isRenewalForSupplier')) {
                $details['isRenewalForSupplier'] = (boolean)$isRenewalForSupplier;
            } else {
                $details['isRenewalForSupplier'] = false;
            }

            if ($contractStartDate = data_get($deal, 'contract.startDate')) {
                $details['newContractStartDate'] = Carbon::createFromTimestamp(strtotime($contractStartDate))->toDateString();
            }


            $newContractEndDate = data_get($deal, 'quoteDetails.ContractEndDate');
            if (!$newContractEndDate) {
                $newContractEndDate = data_get($deal, 'contract.endDate');
                if ($newContractEndDate && strlen($newContractEndDate) <= 2) {
                    if ($contractStartDate) {
                        $newContractEndDate = Carbon::createFromTimestamp(strtotime($contractStartDate))->addMonths($newContractEndDate)->subDay()->toDateString();
                    } else {
                        $newContractEndDate = '';
                    }
                }
            }

            if ($newContractEndDate) {
                $details['newContractEndDate'] = $newContractEndDate;
            }

            /*$isNewConnection = data_get($deal, 'contract.isNewConnection');
            if (!is_null($isNewConnection)) {
                $utility['contractDetails']['isNewConnection'] = filter_var($contractEndDate, FILTER_VALIDATE_BOOLEAN) ? true : false;
            }*/
        }

        return $details;
    }


    /**
     * @param $data
     * @return array
     */
    protected function getSmeDetails($deal)
    {

        $utilityType = data_get($deal, 'utilityType');
        $meterNumber = data_get($deal, 'smeDetails.meterNumber');


        $smeDetails = [];


        if (data_get($deal, 'smeDetails.meterSerialNumbers')) {
            $smeDetails['meterSerialNumbers'] = data_get($deal, 'smeDetails.meterSerialNumbers');
        }

        if (data_get($deal, 'smeDetails.lastReading')) {
            $smeDetails['lastReading'] = data_get($deal, 'smeDetails.lastReading');
        }

        if (data_get($deal, 'smeDetails.isRenewable')) {
            $smeDetails['isRenewable'] = (boolean)data_get($deal, 'smeDetails.isRenewable');
        } else {
            $smeDetails['isRenewable'] = true;
        }

        if (data_get($deal, 'smeDetails.standingChargeType') &&
            in_array(data_get($deal, 'smeDetails.standingChargeType'), ['None', 'Low', 'Standard', 'High'])) {
            $smeDetails['standingChargeType'] = data_get($deal, 'smeDetails.standingChargeType');
        }

        $rates = $this->getRates($deal);

        if (!empty($rates)) {
            $smeDetails['rates'] = $rates;
        }

        if ($usage = (data_get($deal, 'usage.unit') ?: data_get($deal, 'usage.day'))) {
            $smeDetails['usage'] = [
                "unit" => (int)$usage
            ];
        }

        if ($meterNumber) {
            if ($utilityType == 'gas') {
                $smeDetails['mprn'] = $meterNumber;
            } else {
                $smeDetails['mpanBottom'] = $meterNumber;
            }
        }

        if ($utilityType == 'electric' && data_get($deal, 'smeDetails.mpanTop')) {
            $smeDetails['mpanTop'] = data_get($deal, 'smeDetails.mpanTop');
        }

        return $smeDetails;
    }

    protected function getRates($deal)
    {
        $unites = ['pencePerDay', 'pencePerKwh', 'pencePerDayPerKva', 'pencePerKvarh'];
        $rateTypes = [
            'StandingCharge',
            'Unit',
            'Day',
            'Night',
            'Weekend',
            'CapacityCharge',
            'ReactiveCharge',
            'Fits',
            'DirectDebit',
            'ChequeSurcharge'
        ];
        $rates = [];
        $quoteDetails = data_get($deal, 'quoteDetails');

        $DayUnitrate = (float)data_get($quoteDetails, 'DayUnitrate', 0);
        if ($DayUnitrate > 0) {
            $rates[] = [
                'rateType' => 'Day',
                'amount' => $DayUnitrate,
                'uplift' => (float)data_get($quoteDetails, 'Uplift') ?: 0,
                'unit' => 'pencePerKwh'
            ];
        }
        $NightUnitrate = (float)data_get($quoteDetails, 'NightUnitrate', 0);
        if ($NightUnitrate > 0) {
            $rates[] = [
                'rateType' => 'Night',
                'amount' => $NightUnitrate,
                'uplift' => (float)data_get($quoteDetails, 'Uplift') ?: 0,
                'unit' => 'pencePerKwh'
            ];
        }
        $WendUnitrate = (float)data_get($quoteDetails, 'WendUnitrate', 0);
        if ($WendUnitrate > 0) {
            $rates[] = [
                'rateType' => 'Weekend',
                'amount' => $WendUnitrate,
                'uplift' => (float)data_get($quoteDetails, 'Uplift') ?: 0,
                'unit' => 'pencePerKwh'
            ];
        }
        $StandingCharge = (float)data_get($quoteDetails, 'StandingCharge', 0);
        if ($StandingCharge > 0) {
            $rates[] = [
                'rateType' => 'StandingCharge',
                'amount' => $StandingCharge,
                'uplift' => (float)data_get($quoteDetails, 'StandingChargeUplift') ?: 0,
                'unit' => 'pencePerDay'
            ];
        }

        return $rates;
    }


    /**
     * @param $data
     * @return array
     */
    protected function getContact($data)
    {
        $contact = [];
        foreach ($this->contact_mapping as $k => $cm) {
            if ($k == 'phoneNumbers') {
                foreach ($cm as $m) {
                    $value = data_get($data, $m);
                    if ($m !== 'mobile' && !$value) {
                        $value = '02039219000';
                    }
                    if (!$this->nullOrEmpty($value)) {
                        $contact['phoneNumbers'][] = [
                            'number' => $value,
                            'type' => ($m == 'mobile' ? $m : 'landline')
                        ];
                    }
                }
            } else if ($k == 'dateOfBirth') {
                $value = data_get($data, $cm);
                $value = Carbon::createFromTimestamp(strtotime($value))->format('Y-m-d');
                $contact[$k] = $value;
            } else {
                $value = data_get($data, $cm);
                if (!$this->nullOrEmpty($value)) {
                    if ($k == 'title') {
                        $value = trim($value, '.');
                    }
                    $contact[$k] = $value;
                }
            }
        }

        $contact['jobTitle'] = 'Director';

        return $contact;
    }

    /**
     * @param $deal
     * @return array
     */
    protected function getCustomerContact($deal): array
    {
        $contact = $this->getContact(array_merge(data_get($deal, 'customer', []), data_get($deal, 'customerContact', [])));

        foreach ($this->address_mapping as $am) {
            if (data_get($deal, 'customer.' . $am)) {
                $contact['homeAddress']['address'][$am] = data_get($deal, 'customer.' . $am);
            }
        }
        if ($moveInDate = data_get($deal, "customer.moveInDate")) {
            $moveInDate = Carbon::createFromTimestamp(strtotime($moveInDate))->format('Y-m-d');
            $contact['homeAddress']['moveInDate'] = $moveInDate;

            /*foreach ($this->address_mapping as $am) {
                if (data_get($deal, 'customer.' . $am)) {
                    $contact['homeAddress']['previousAddress']['address'][$am] = data_get($deal, 'customer.' . $am);
                }
            }*/

        }
        return $contact;
    }

    /**
     * @param $deal
     * @return array
     */
    protected function getSiteAddress($deal): array
    {
        $address = [];
        foreach ($this->address_mapping as $am) {
            if (data_get($deal, 'site.' . $am)) {
                $address[$am] = data_get($deal, 'site.' . $am);
            }
        }
        return $address;
    }

    /**
     * @param $deal
     * @return array
     */
    protected function getBankDetails($deal): array
    {
        $mapping = [
            "name",
            "branchName",
            "sortCode",
            "accountNumber",
            "accountName",
            "requires2signatures",
        ];
        $bankAddressMapping = [
            'organisationName',
            'departmentName',
            'subBuildingName',
            'buildingName',
            'buildingNumber',
            'dependentThoroughfareName',
            'thoroughfareName',
            'doubleDependentLocality',
            'dependentLocality',
            'postTown',
            'county',
            'postcode',
            'poBox'
        ];
        $bankDetails = [];
        foreach ($mapping as $am) {
            if (data_get($deal, 'bankDetails.' . $am)) {
                $bankDetails[$am] = data_get($deal, 'bankDetails.' . $am);
            }
        }
        $bankAddress = [];
        foreach ($bankAddressMapping as $am) {
            if (data_get($deal, 'bankAddress.' . $am)) {
                $bankAddress[$am] = data_get($deal, 'bankAddress.' . $am);
            }
        }

        if (!empty($bankAddress)) {
            $bankDetails['address'] = $bankAddress;
        }
        return $bankDetails;
    }

    /**
     * @param $deal
     * @return array
     */
    protected function getBillingAddress($deal): array
    {
        $address = [];
        foreach ($this->address_mapping as $am) {
            if (data_get($deal, 'billingAddress.' . $am)) {
                $address[$am] = data_get($deal, 'billingAddress.' . $am);
            }
        }
        return $address;
    }

    /**
     * @param $deal
     * @return array|string[]
     */
    protected function getCompany($deal): array
    {
        $attributeMap = [
            'name',
            'tradingAs',
            'type',
            'number',
            'dateOfIncorporation',
            'dateOfDissolution',
            'isScottishCharity',
            'isMicroBusiness',
            'sicCodes',
            'dataSource'
        ];

        $partnerMapping = [
            'title' => 'title',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'dateOfBirth' => 'dob',
            'email' => 'email',
        ];

        $company = [];
        if (!empty(data_get($deal, 'company'))) {
            $company = [
                "dataSource" => "Broker"
            ];

            $type = data_get($deal, 'company.type');
            if (strtolower($type) == 'ltd') {
                $type = 'Limited';
            }
            if (strtolower($type) == 'plc') {
                $type = 'PLC';
            }
            if (strtolower($type) == 'llp') {
                $type = 'LimitedLiabilityPartnership';
            }

            data_set($deal, 'company.type', $type);


            foreach ($attributeMap as $atm) {
                if ($value = data_get($deal, 'company.' . $atm)) {
                    if ($atm == 'isMicroBusiness') {
                        $value = (bool)$value;
                    }
                    if ($atm == 'sicCodes') {
                        if (is_array($value)) {
                            $codes = [];
                            foreach (array_values($value) as $v) {
                                $codes[] = [
                                    'code' => (string)$v
                                ];
                            }
                            $value = $codes;
                        }
                    }
                    $company[$atm] = $value;
                }
            }


            $companyAddress = array_filter(data_get($deal, 'company', []), function ($val, $key) {
                return in_array($key, $this->address_mapping) && !empty($val);
            }, ARRAY_FILTER_USE_BOTH);

            if (empty($companyAddress)) {
                foreach ($this->address_mapping as $adm) {
                    if (data_get($deal, 'customer.' . $adm)) {
                        $companyAddress[$adm] = data_get($deal, 'customer.' . $adm);
                    }
                }
            }

            if (!empty($companyAddress)) {
                $company['address'] = $companyAddress;
            }

            if ($type == 'LimitedLiabilityPartnership' || $type == 'LLP') {

                $partner1 = data_get($deal, 'company.partner1', []);
                $partner2 = data_get($deal, 'company.partner2', []);

                if (!empty($partner1) || !empty($partner2)) {
                    if (!empty($partner1)) {
                        $partner = [];
                        foreach ($partnerMapping as $k => $v) {
                            if ($value = data_get($partner1, $v)) {
                                $partner[$k] = $value;
                            }
                        }
                        foreach ($this->address_mapping as $adm) {
                            if ($value = data_get($partner1, $adm)) {
                                $partner['homeAddress']['address'][$adm] = $value;
                            }
                        }
                        if (!empty($partner)) {
                            $company['contacts'][] = $partner;
                        }
                    }
                    if (!empty($partner2)) {
                        $partner = [];
                        foreach ($partnerMapping as $k => $v) {
                            if ($value = data_get($partner2, $v)) {
                                $partner[$k] = $value;
                            }
                        }
                        foreach ($this->address_mapping as $adm) {
                            if ($value = data_get($partner2, $adm)) {
                                $partner['homeAddress']['address'][$adm] = $value;
                            }
                        }
                        if (!empty($partner)) {
                            $company['contacts'][] = $partner;
                        }
                    }
                } else {
                    $customer_contact = $this->getCustomerContact($deal);
                    if (!empty($customer_contact)) {
                        if (!isset($customer_contact['isACompanyOwner'])) {
                            $customer_contact['isACompanyOwner'] = true;
                        }
                        $company['contacts'][] = $customer_contact;
                    }
                }
            } else {
                $customer_contact = $this->getCustomerContact($deal);
                if (!empty($customer_contact)) {
                    if (!isset($customer_contact['isACompanyOwner'])) {
                        $customer_contact['isACompanyOwner'] = true;
                    }
                    $company['contacts'][] = $customer_contact;
                }
            }
            if (!isset($company['tradingAs'])) {
                $company['tradingAs'] = 'Others';
            }
        }

        return $company;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function nullOrEmpty($value)
    {
        if (is_array($value) || is_string($value)) {
            return empty($value);
        }

        if (is_numeric($value)) {
            return false;
        }

        if (is_bool($value)) {
            return false;
        }

        return is_null($value);
    }

    public function powwrErrors($response_body)
    {
        $errors = [];
        $_errors = data_get($response_body, 'extensions.errors');

        if (is_array($_errors)) {
            foreach ($_errors as $error) {
                $desc = data_get($error, 'description');
                $type = data_get($error, 'type');
                if ($type) {
                    $desc .= ' - ' . $type;
                }

                $properties = data_get($error, 'properties', []);
                if (!empty($properties)) {
                    $path = data_get($properties[0] ?? [], 'path');
                    //$path = str_replace('.', '->', $path);

                    if ($path) {
                        $desc .= ' - (' . $path . ')';
                    }
                }
                $errors[] = $desc;
            }
        }

        return $errors;
    }
}
