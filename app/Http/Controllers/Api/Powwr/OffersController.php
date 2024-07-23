<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\Requests\OffersRequest;
use App\Http\Integrations\Powwr\Services;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\PowwrOffers;
use App\Models\Deals;
use App\Models\Suppliers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group Powwr
 * @unauthenticated
 */
class OffersController extends ApiController
{

    protected $halfHourlySuppliers = [
        'British Gas',
        'British Gas Plus',
        'Smartest Energy',
        'Drax',
        'Engie',
    ];

    protected $ygp_plans = [
        0 => [
            'Duration' => 12,
            'PlanType' => 'Economy',
            'Uplift' => 3,
        ],
        1 => [
            'Duration' => 12,
            'PlanType' => 'Economy|Renewable',
            'Uplift' => 3,
        ],
        2 => [
            'Duration' => 12,
            'PlanType' => 'Premium HR',
            'Uplift' => 3,
        ],
        3 => [
            'Duration' => 12,
            'PlanType' => 'Premium HR|Renewable',
            'Uplift' => 3,
        ],
        4 => [
            'Duration' => 24,
            'PlanType' => 'Economy',
            'Uplift' => 3,
        ],
        5 => [
            'Duration' => 24,
            'PlanType' => 'Economy|Renewable',
            'Uplift' => 3,
        ],
        6 => [
            'Duration' => 24,
            'PlanType' => 'Premium HR',
            'Uplift' => 3,
        ],
        7 => [
            'Duration' => 24,
            'PlanType' => 'Premium HR|Renewable',
            'Uplift' => 3,
        ],
        8 => [
            'Duration' => 36,
            'PlanType' => 'Economy',
            'Uplift' => 3,
        ],
        9 => [
            'Duration' => 36,
            'PlanType' => 'Economy|Renewable',
            'Uplift' => 3,
        ],
        10 => [
            'Duration' => 36,
            'PlanType' => 'Premium HR',
            'Uplift' => 3,
        ],
        11 => [
            'Duration' => 36,
            'PlanType' => 'Premium HR|Renewable',
            'Uplift' => 3,
        ],
        12 => [
            'Duration' => 48,
            'PlanType' => 'Economy',
            'Uplift' => 3,
        ],
        13 => [
            'Duration' => 48,
            'PlanType' => 'Economy|Renewable',
            'Uplift' => 3,
        ],
        14 => [
            'Duration' => 48,
            'PlanType' => 'Premium HR',
            'Uplift' => 3,
        ],
        15 => [
            'Duration' => 48,
            'PlanType' => 'Premium HR|Renewable',
            'Uplift' => 3,
        ],
        16 => [
            'Duration' => 60,
            'PlanType' => 'Economy',
            'Uplift' => 3,
        ],
        17 => [
            'Duration' => 36,
            'PlanType' => 'Economy|Renewable',
            'Uplift' => 3,
        ],
        18 => [
            'Duration' => 60,
            'PlanType' => 'Premium HR',
            'Uplift' => 3,
        ],
        19 => [
            'Duration' => 36,
            'PlanType' => 'Premium HR|Renewable',
            'Uplift' => 3,
        ],
    ];

    protected $britishGasDefaultPlans = [
        [
            "Duration" => 12,
            "PlanType" => "",

        ],
        [
            "Duration" => 24,
            "PlanType" => ""

        ],
        [
            "Duration" => 36,
            "PlanType" => ""
        ],
        [
            "Duration" => 48,
            "PlanType" => ""

        ],
        [
            "Duration" => 60,
            "PlanType" => ""
        ]
    ];

    /**
     * Offers
     *
     * Offers from udcoreapi.co.uk
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PowwrOffers $request)
    {
        set_time_limit(1000);
        ini_set('max_execution_time', 1000);

        $utilityType = $request->utilityType;

        $request_data = $this->getBody($request);
        //dd($request_data);

        $connector = new Services();

        $offer_request = new OffersRequest($utilityType, $request_data);

        $offer_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $apiResponse = $connector->send($offer_request);

        $apiResponseBody = $apiResponse->body();
        if (isJson($apiResponseBody)) {
            $apiResponseBody = json_decode($apiResponseBody, true);
        }

        if ($apiResponse->status() == 200) {
            $preferred_suppliers = Suppliers::$preferred_suppliers;

            $result = $apiResponseBody['GetGasRatesResult'] ?? $apiResponseBody['GetElectricRatesResult'] ?? [];
            $resultRates = $result['Rates'] ?? [];
            $rates = [];

            foreach ($resultRates as $key => $rate) {
                $Supplier = $rate['Supplier'] ?? '';
                $PlanType = $rate['PlanType'] ?? '';
                $rate['Preferred'] = ($rate['Term'] == 24 && in_array($Supplier, $preferred_suppliers) ? 1 : 0);

                if (
                    strtolower($Supplier) == 'yorkshire gas and power' && $PlanType &&
                    !in_array($PlanType, ['Economy', 'Premium HR', 'Economy|Renewable', 'Premium HR|Renewable'])
                ) {
                    continue;
                }
                if (in_array(strtolower($Supplier), ['yorkshire gas and power', 'british gas', 'british gas lite', 'totalenergies', 'opus energy', 'yu energy'])) {
                    if ($rate['RawAnnualPrice']) {
                        $key = md5($PlanType . $rate['Term'] . $rate['RawAnnualPrice']);
                        $rates[$key] = $rate;
                    }
                } else {
                    $rates[$key] = $rate;
                }
            }

            $rates = collect(array_values($rates))->reject(function ($item) {
                return ($item['RawAnnualPrice'] == null || $item['RawAnnualPrice'] == '');
            });

            $minPrice = $rates->where('RawAnnualPrice', '>', 0)->min('RawAnnualPrice');
            $rates->transform(function ($row) use ($minPrice, $preferred_suppliers) {
                $row['BestDeal'] = ($row['RawAnnualPrice'] == $minPrice);
                return $row;
            });

            $result['Rates'] = $rates->sortBy([
                ['BestDeal', 'desc'],
                ['Preferred', 'desc'],
            ])->values();

            $responseData = [
                'success' => true,
                'data' => $result
            ];
        } else {
            $responseData = [
                'success' => false,
                'data' => $apiResponseBody
            ];
        }

        if (app()->isProduction()) {
            unset($apiResponseBody['warnings']);
        } else {
            $responseData['api_payload'] = $offer_request->body()->all();
        }

        return response()->json($responseData, $apiResponse->status());
    }

    /**
     *
     * @param Request $request
     * @return array
     *
     */
    protected function getBody(Request $request)
    {

        $deal = null;
        if ($dealId = $request->dealId) {
            $deal = Deals::where('id', $dealId)->first();
        }

        $allowedSuppliers = $deal ? $deal->allowedSuppliers : [];
        $suppliers = Suppliers::apiSuppliers();

        $utilityType = strtolower($request->utilityType);
        $meterNumber = $request->meterNumber;
        $currentSupplier = $request->currentSupplier;
        $contractEnded = $request->contractEnded;
        $newSuppliers = $request->input('newSupplier');

        $uplift = $request->input('plans.uplift');
        $upliftSupplier = $request->input('plans.supplier');

        if ($upliftSupplier && $upliftSupplier != $newSuppliers) {
            $uplift = null;
        }

        $quoteDetails = [];

        $attributeMapping = [
            'quoteReference' => 'QuoteReference',
            'postCode' => 'PostCode',
            'renewal' => 'Renewal',
            'currentSupplier' => 'CurrentSupplier',
            'cot' => 'COT',
            'cotDate' => 'CotDate',
            'outOfContract' => 'OutOfContract',
            'uplift' => 'Uplift',
            //'standingChargeUplift' => 'StandingChargeUplift',
            //'paymentMethod' => 'PaymentMethod',
            //'sortByCommission' => 'SortByCommission',
            'businessType' => 'BusinessType',
        ];

        $quoteDetails['SupplyType'] = $utilityType == 'gas' ? 'gas' : 'elec';
        $quoteDetails['Uplift'] = 2;

        foreach ($attributeMapping as $key => $mapped) {
            if ($request->filled($key)) {
                $quoteDetails[$mapped] = $request->input($key);
            } else {
                if (in_array($key, ['renewal', 'cot', 'outOfContract', 'sortByCommission'])) {
                    $quoteDetails[$mapped] = false;
                } elseif (in_array($key, ['standingChargeUplift'])) {
                    $quoteDetails[$mapped] = 0.2;
                } /*else {
                    $quoteDetails[$mapped] = null;
                }*/
            }
        }

        if (!isset($quoteDetails['PaymentMethod'])) {
            $quoteDetails['PaymentMethod'] = 'Direct Debit (Monthly)';
        }

        $supplyMapping = [
            'contractRenewalDate' => 'ContractRenewalDate',
            'contractEndDate' => 'ContractEndDate',
            'newContractEndDate' => 'NewContractEndDate',
            'measurementClass' => 'MeasurementClass',
            'currentSupplier' => 'CurentSupplier',
            'smartMeter' => 'SmartMeter',
        ];

        $supply = [];
        foreach ($supplyMapping as $key => $mapped) {
            if ($request->filled($key)) {
                $value = $request->input($key);
                if (in_array($key, ['contractEndDate', 'newContractEndDate', 'contractRenewalDate'])) {
                    $value = date('Y-m-d', strtotime($value));
                }
                $supply[$mapped] = $value;
            } else {
                $supply[$mapped] = '';
            }
        }

        if ($contractEnded) {
            $supply['ContractEndDate'] = Carbon::now()->toDateString();
        }

        if (!isset($supply['SmartMeter']) || !$supply['SmartMeter']) {
            $supply['SmartMeter'] = false;
        }

        if ($utilityType == 'gas') {
            $gasSupply = $supply;

            $gasSupply['MPR'] = $meterNumber;

            if ($cons_amount = ($request->input('consumption.amount') ?: $request->input('consumption.day'))) {
                $gasSupply['Consumption'] = [
                    "Amount" => $cons_amount
                ];
            }

            $quoteDetails['GasSupply'] = $gasSupply;
        } else {
            $electricSupply = $supply;

            if ($newSuppliers == 'abc') {
                $electricSupply['StandardSettlementConfig'] = 0317;
            }

            $electricSupply['MPANTop'] = $request->input('mpanTop');
            $electricSupply['MPANBottom'] = $meterNumber;
            $meterType = $request->input('meterType');
            if ($meterType && !Str::contains($meterType, '@')) {
                $electricSupply['MeterType'] = $meterType;
            }

            $prompts = $request->input('prompts', []);

            if (!is_array($prompts)) {
                $prompts = explode(',', $prompts);
            }

            $cons_day = ($request->input('consumption.day') ?: $request->input('consumption.amount'));
            $cons_night = $request->input('consumption.night') ?: 0;
            $cons_wend = $request->input('consumption.wend') ?: 0;

            if ($cons_day) {
                $needNightConsumption = in_array('Night', $prompts);

                $needWendConsumption = in_array('Wend', $prompts) || in_array('Weekend', $prompts);

                if (($needNightConsumption && !$cons_night) || $needWendConsumption && !$cons_wend) {
                    $dc = $cons_day;
                    if ($needNightConsumption && !$cons_night) {
                        $cons_night = ($cons_day * 15) / 100;
                        $dc = $dc - $cons_night;
                    }

                    if ($needWendConsumption && !$cons_wend) {
                        $cons_wend = ($cons_day * 15) / 100;
                        $dc = $dc - $cons_night;
                    }

                    $cons_day = $dc;
                }

                $electricSupply['DayConsumption'] = [
                    "Amount" => $cons_day,
                    "Type" => 'Day'
                ];

                $electricSupply['NightConsumption'] = [
                    "Amount" => $cons_night,
                    "Type" => 'Night'
                ];

                $electricSupply['WendConsumption'] = [
                    "Amount" => $cons_wend,
                    "Type" => 'Weekend'
                ];
            }
            if ($cons_kva = $request->input('consumption.kva')) {
                $electricSupply['KvaConsumption'] = [
                    "Amount" => $cons_kva,
                    "Type" => 'kVA'
                ];
            }
            /*if ($cons_kvarh = $request->input('consumption.kvarh')) {
                $electricSupply['KvarhConsumption'] = [
                    "Amount" => $cons_kvarh
                ];
            }*/

            if ($measurementClass = $request->input('measurementClass', '')) {
                $electricSupply['MeasurementClass'] = $measurementClass;
            }

            $quoteDetails['ElectricSupply'] = $electricSupply;
        }

        $plan = [];

        $duration = $request->input('plans.duration');
        if ($duration && strlen(intval($duration)) == 1) {
            $duration = $duration * 12;
        }

        $QuoteDefinitions = [];
        if ($newSuppliers && !is_array($newSuppliers)) {
            $newSuppliers = explode(',', $newSuppliers);
        }

        if (!empty($newSuppliers)) {
            foreach ($newSuppliers as $name) {
                if (empty($allowedSuppliers) || in_array($name, $allowedSuppliers)) {
                    $plans = $suppliers[$name] ?? [];
                    if (!empty($plans)) {
                        $IsRenewal = $currentSupplier == $name;
                        if ($IsRenewal) {
                            $quoteDetails['Renewal'] = true;
                        }
                        $_plans = $this->getPlans($name, $plans, $utilityType, $duration, $IsRenewal, $uplift);
                        if (!empty($_plans)) {
                            $QuoteDefinitions[] = [
                                'Supplier' => $name,
                                'Plans' => $_plans,
                            ];
                        }
                    }
                }
            }
            $quoteDetails['QuoteDefinitions'] = $QuoteDefinitions;
        } else {
            foreach ($suppliers as $supplier => $plans) {
                if (empty($allowedSuppliers) || in_array($supplier, $allowedSuppliers)) {
                    if (!empty($plans) && $supplier != 'BES') {
                        $IsRenewal = $currentSupplier == $supplier;
                        $_plans = $this->getPlans($supplier, $plans, $utilityType, $duration, $IsRenewal, $uplift);
                        if (!empty($_plans)) {
                            $QuoteDefinitions[] = [
                                'Supplier' => $supplier,
                                'Plans' => $_plans,
                            ];
                        }
                    }
                }
            }
            $quoteDetails['QuoteDefinitions'] = $QuoteDefinitions;
        }

        $quoteDetails['SortByCommission'] = (boolean)$request->input('sortByCommission', false);

        return $quoteDetails;
    }

    /**
     * Array(
     *    [Description] =>
     *    [Duration] => 12
     *    [HalfHourly] => 1
     *    [HalfHourlyNumberOfRates] => 3
     *    [IncludeMOPCharges] =>
     *    [IsRenewable] => 1
     *    [LastPriceChange] => 2023-12-20 10:32:49Z
     *    [PlanType] => 3Rate|Renewable
     *    [Pricebook] =>
     *    [Product] => HH, 3Rate, Renewable
     *    [StandingChargeType] =>
     *    [Supplier] => British Gas
     *    [Utility] => Electric
     *    [Version] =>
     * )
     *
     * @param $plans
     * @return array
     */

    protected function getPlans($supplier, $plans, $utilityType, $duration, $IsRenewal, $CustomUplift = null)
    {
        $halfHourly = request('halfHourly', false);
        $output = [];
        if ($halfHourly && !in_array($supplier, $this->halfHourlySuppliers)) {
            return $output;
        }

        if (strtolower($supplier) == 'yorkshire gas and power') {
            $plans = array_values($this->ygp_plans);
            foreach ($plans as $plan) {
                $PlanDuration = $plan['Duration'];
                if ($PlanDuration && $PlanDuration % 12 == 0) {
                    if (!$duration || $PlanDuration == $duration) {
                        if ($CustomUplift) {
                            $plan['Uplift'] = $CustomUplift;
                        }
                        $output[] = $plan;
                    }
                }
            }
            return array_values($output);
        }

        if (strtolower($supplier) == 'british gas') {
            $bgPlans = array_values($this->britishGasDefaultPlans);
            foreach ($bgPlans as $bgPlan) {
                $bgPlanDuration = $bgPlan['Duration'];
                if (!$duration || $bgPlanDuration == $duration) {
                    $bgUplift = $CustomUplift ?: $this->getUplift($supplier, $utilityType, $bgPlan['Duration'] ?? 12, $IsRenewal);
                    $output[] = [
                        'Duration' => $bgPlan['Duration'],
                        'PlanType' => $bgPlan['PlanType'],
                        'Uplift' => $bgUplift,
                    ];
                }
            }
        }

        $plans = array_filter($plans, function ($a) use ($utilityType) {
            return strtolower($a['Utility']) == strtolower($utilityType);
        });

        foreach ($plans as $plan) {
            $Utility = strtolower($plan['Utility']);
            $PlanDuration = $plan['Duration'];
            $PriceChangeDate = Carbon::parse($plan['LastPriceChange'])->format('Y-m-d H:i');
            $LastPriceChange = Carbon::parse($plan['LastPriceChange'])->diffInMonths();
            $PlanType = $plan['PlanType'];

            /*if (
                $PlanType && strtolower($supplier) == 'yorkshire gas and power' &&
                !in_array(strtolower($PlanType), ['economy', 'premium hr', 'economy|renewable', 'premium hr|renewable'])
            ) {
                continue;
            }*/

            if (
                strtolower($supplier) == 'utilita' ||
                (strtolower($supplier) == 'british gas' && strtolower($PlanType) == 'non-renewable') ||
                (strtolower($supplier) == 'engie' && Str::contains(strtolower($PlanType), 'eoc')) ||
                (
                    strtolower($supplier) == 'scottish and southern' &&
                    Str::contains(strtolower($PlanType), 'renewable') &&
                    $utilityType == 'electric'
                )
            ) {
                continue;
            }

            if (!$PlanType && $Utility == 'electric' && strtolower($supplier) == 'totalenergies') {
                $PlanType = 'Eco Energy|Renewable';
            }

            if ($LastPriceChange <= 2 && $PlanDuration && $PlanDuration % 12 == 0) {
                if ($Utility == $utilityType && (!$duration || $PlanDuration == $duration)) {
                    $uplift = $CustomUplift ?: $this->getUplift($supplier, $utilityType, $PlanDuration, $IsRenewal);
                    $arrayKey = Str::slug($supplier . $PriceChangeDate . $PlanDuration . $PlanType);
                    if (!key_exists($arrayKey, $output)) {
                        $output[$arrayKey] = [
                            'Duration' => $PlanDuration,
                            'PlanType' => $PlanType,
                            'Uplift' => $uplift ?: 2
                        ];
                    }
                }
            }
        }

        $output = array_values($output);

        return array_values($output);
    }

    protected function getUplift($supplier, $type, $term, $IsRenewal = false)
    {

        $plans = Suppliers::where('name', $supplier)->first();

        $type = strtolower($type);
        $term = $term > 9 ? $term / 12 : $term;
        $key = $IsRenewal ? 'renewal' : 'uplift';

        $uplifts = $plans->uplifts[$type] ?? null;
        if (is_array($uplifts)) {
            return isset($uplifts[$term]) ? $uplifts[$term][$key] : $uplifts[1][$key];
        }
        return null;
    }
}
