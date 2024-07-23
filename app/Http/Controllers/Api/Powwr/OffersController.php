<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\Requests\OffersRequest;
use App\Http\Integrations\Powwr\Services;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\PowwrOffers;
use App\Models\PowwrSupplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OffersController extends ApiController
{

    protected $halfHourlySuppliers = [
        'British Gas',
        'British Gas Plus',
        'Smartest Energy',
        'Drax',
        'Engie',
    ];

    /**
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
            $result = $apiResponseBody['GetGasRatesResult'] ?? $apiResponseBody['GetElectricRatesResult'] ?? [];
            $rates = $result['Rates'] ?? [];

            $rates = collect($rates)->reject(function ($item) {
                return ($item['AnnualPrice'] == null || $item['AnnualPrice'] == '');
            });

            $preferred_suppliers = PowwrSupplier::$preferred_suppliers;

            $minPrice = $rates->where('RawAnnualPrice', '>', 0)->min('RawAnnualPrice');
            $rates->transform(function ($row) use ($minPrice, $preferred_suppliers) {
                $Supplier = $row['Supplier'] ?? '';
                $row['BestDeal'] = ($row['RawAnnualPrice'] == $minPrice);
                $row['Preferred'] = ($row['Term'] == 24 && in_array($Supplier, $preferred_suppliers) ? 1 : 0);
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

    protected function getBody(Request $request)
    {
        $suppliers = PowwrSupplier::apiSuppliers();

        $utilityType = strtolower($request->utilityType);
        $meterNumber = $request->meterNumber;
        $currentSupplier = $request->currentSupplierName;

        $quoteDetails = [];

        $attributeMapping = [
            'quoteReference' => 'QuoteReference',
            'postCode' => 'PostCode',
            'renewal' => 'Renewal',
            'currentSupplierName' => 'CurrentSupplier',
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
        $quoteDetails['Uplift'] = 0.2;

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
            'curentSupplier' => 'CurentSupplier',
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
                $supply[$mapped] = null;
            }
        }

        if (!isset($supply['SmartMeter'])) {
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

            $electricSupply['MPANTop'] = $request->input('MPANTop');
            $electricSupply['MPANBottom'] = $meterNumber;
            $meterType = $request->input('meterType');
            if ($meterType && !Str::contains($meterType, '@')) {
                $electricSupply['MeterType'] = $meterType;
            }
            $cons_day = ($request->input('consumption.day') ?: $request->input('consumption.amount'));

            if ($cons_day) {
                $electricSupply['DayConsumption'] = [
                    "Amount" => $cons_day,
                    "Type" => 'Day'
                ];

                $cons_night = $request->input('consumption.night') ?: 0;
                $electricSupply['NightConsumption'] = [
                    "Amount" => $cons_night,
                    "Type" => 'Night'
                ];

                $cons_wend = $request->input('consumption.wend') ?: 0;
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
        $newSuppliers = $request->input('newSupplierName');
        if ($newSuppliers && !is_array($newSuppliers)) {
            $newSuppliers = explode(',', $newSuppliers);
        }

        if (!empty($newSuppliers)) {
            foreach ($newSuppliers as $name) {
                $IsRenewal = $currentSupplier == $name;
                $plans = $suppliers[$name] ?? [];
                if (!empty($plans)) {
                    $_plans = $this->getPlans($name, $plans, $utilityType, $duration, $IsRenewal);
                    if (!empty($_plans)) {
                        $QuoteDefinitions[] = [
                            'Supplier' => $name,
                            'Plans' => $_plans,
                        ];
                    }
                }
            }
            $quoteDetails['QuoteDefinitions'] = $QuoteDefinitions;
        } else {
            foreach ($suppliers as $supplier => $plans) {
                if (!empty($plans) && $supplier != 'BES') {
                    $IsRenewal = $currentSupplier == $supplier;
                    $_plans = $this->getPlans($supplier, $plans, $utilityType, $duration, $IsRenewal);
                    if (!empty($_plans)) {
                        $QuoteDefinitions[] = [
                            'Supplier' => $supplier,
                            'Plans' => $_plans,
                        ];
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

    protected function getPlans($supplier, $plans, $utilityType, $duration, $IsRenewal)
    {
        $halfHourly = request('halfHourly', false);
        $output = [];
        if ($halfHourly && !in_array($supplier, $this->halfHourlySuppliers)) {
            return $output;
        }
        foreach ($plans as $key => $plan) {

            $PlanType = $plan['PlanType'] ?: '';
            $Utility = $plan['Utility'];
            $PlanDuration = $plan['Duration'];
            $LastPriceChange = Carbon::parse($plan['LastPriceChange'])->diffInMonths();

            if ($LastPriceChange <= 2) {
                if (strtolower($Utility) == $utilityType && (!$duration || $PlanDuration == $duration)) {
                    $uplift = $this->getUplift($supplier, $utilityType, $PlanDuration, $IsRenewal);
                    $arrayKey = md5($key . $PlanDuration . $PlanType);
                    if (!key_exists($arrayKey, $output)) {
                        $output[$arrayKey] = [
                            'Duration' => $PlanDuration,
                            'PlanType' => $PlanType,
                            'Uplift' => $uplift ?: 4
                        ];
                        if(!$PlanType){
                            //unset($output[$arrayKey]['PlanType']);
                        }
                    }
                }
            }
        }
        return array_values($output);
    }

    protected function getUplift($supplier, $type, $term, $IsRenewal = false)
    {

        $list = [
            'British Gas' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'British Gas Lite' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'British Gas Plus' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'Corona Energy' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => null]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => null]
                ]
            ],
            'Drax' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'Dyce Energy' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => 1]
                ],
                'gas' => [
                    1 => ['uplift' => 3, 'renewal' => 1]
                ]
            ],
            'E-ON Next' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'EDF' => [
                'electric' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5],
                    2 => ['uplift' => 1.5, 'renewal' => 1.5],
                    3 => ['uplift' => 1.5, 'renewal' => 1.5],
                    4 => ['uplift' => 1.5, 'renewal' => 1.5],
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5],
                    2 => ['uplift' => 1.5, 'renewal' => 1.5],
                    3 => ['uplift' => 1.5, 'renewal' => 1.5],
                    4 => ['uplift' => 1.5, 'renewal' => 1.5],
                ]
            ],
            'Engie' => [
                'electric' => [
                    1 => ['uplift' => 4, 'renewal' => 1]
                ],
                'gas' => [
                    1 => ['uplift' => 2.5, 'renewal' => 1]
                ]
            ],
            'OPUS' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => null]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => null]
                ]
            ],
            'Scottish And Southern' => [
                'electric' => [
                    1 => ['uplift' => 2.5, 'renewal' => 2.5]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'Scottish Power' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2],
                    2 => ['uplift' => 2, 'renewal' => 2],
                    3 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 0, 'renewal' => 1],
                    2 => ['uplift' => 0, 'renewal' => 1],
                    3 => ['uplift' => 0, 'renewal' => 0],
                ]
            ],
            'SEFE Energy' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'Smartest Energy' => [
                'electric' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ],
                'gas' => [
                    1 => ['uplift' => 1, 'renewal' => 1]
                ]
            ],
            'TotalEnergies' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => 3]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'Utilita' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => 3],
                    2 => ['uplift' => 3, 'renewal' => 3],
                    3 => ['uplift' => 3, 'renewal' => 3]
                ],
                'gas' => [
                    1 => ['uplift' => 0, 'renewal' => null],
                    2 => ['uplift' => 0, 'renewal' => null],
                    3 => ['uplift' => 0, 'renewal' => null]
                ]
            ],
            'Valda Energy' => [
                'electric' => [
                    1 => ['uplift' => 2.5, 'renewal' => 2.5]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'Yorkshire Gas And Power' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => null]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => null]
                ]
            ],
            'Yu Energy' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ]
        ];

        $uplifts = $list[$supplier][$type] ?? null;
        if (is_array($uplifts)) {
            $key = $IsRenewal ? 'renewal' : 'uplift';
            return isset($uplifts[$term]) ? $uplifts[$term][$key] : $uplifts[1][$key];
        }
        return null;
    }
}
