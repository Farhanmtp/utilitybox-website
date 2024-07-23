<?php

namespace App\Console\Commands;

use App\Http\Integrations\Powwr\Requests\SuppliersRequest;
use App\Http\Integrations\Powwr\UdCoreApiConnector;
use App\Models\Suppliers;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class importSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:suppliers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $suppliers = $this->getSuppliers();

        foreach ($suppliers as $supplier => $_plans) {

            $modal = Suppliers::where('slug', Str::slug($supplier))
                ->orWhere('name', $supplier)
                ->orWhere('name', str_replace(' ', '', $supplier))
                ->when($supplier == 'BES', function ($q) {
                    $q->orWhere('name', 'BES Utilities');
                })
                ->when($supplier == 'British Gas', function ($q) {
                    $q->orWhere('name', 'British Gas Business');
                })
                ->when($supplier == 'EDF Energy', function ($q) {
                    $q->orWhere('name', 'EDF');
                })
                ->when($supplier == 'Opus Energy', function ($q) {
                    $q->orWhere('name', 'Opus');
                })
                ->when($supplier == 'SEFE Energy', function ($q) {
                    $q->orWhere('name', 'SEFE');
                })
                ->when($supplier == 'Scottish And Southern', function ($q) {
                    $q->orWhere('name', 'SSE');
                })
                ->when($supplier == 'TotalEnergies', function ($q) {
                    $q->orWhere('name', 'Total Energies')->orWhere('name', 'Total Energy');
                })
                ->firstOrNew();

            $plans = $this->getPlans($_plans);

            $utilities = array_filter(array_unique(array_map(function ($a) {
                return $a['Utility'];
            }, $_plans)));

            $modal->name = $supplier;
            $modal->slug = Str::slug($supplier);
            if (!$modal->logo) {
                $modal->logo = Str::slug($supplier) . '.png';
            }

            if (!$modal->uplifts) {
                $modal->uplifts = $this->getUplifts($supplier);
            }

            if (count($utilities) == 2) {
                $modal->supplier_type = 'B';
            } elseif (in_array('Gas', $utilities)) {
                $modal->supplier_type = 'G';
            } elseif (in_array('Electric', $utilities)) {
                $modal->supplier_type = 'E';
            }

            $modal->status = 1;
            $modal->plans = $plans;
            $modal->save();
        }
    }

    protected function getSuppliers()
    {

        $connector = new UdCoreApiConnector();

        $api_request = new SuppliersRequest();

        $api_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($api_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200) {
            $list = $response_body['GetLatestPriceChangeResult']['PriceChanges'] ?? [];

            if (!empty($list)) {
                $list = collect($list)
                    ->groupBy('Supplier')
                    ->toArray();
                ksort($list);
            }
            return $list;
        }

        return [];
    }


    protected function getPlans($plans)
    {
        $output = [];
        foreach ($plans as $plan) {
            $supplier = $plan['Supplier'];
            $PlanDuration = $plan['Duration'];
            $PriceChangeDate = Carbon::parse($plan['LastPriceChange'])->format('Y-m-d H:i');
            $LastPriceChange = Carbon::parse($plan['LastPriceChange'])->diffInMonths();
            $PlanType = $plan['PlanType'];

            if ($LastPriceChange <= 2 && $PlanDuration && $PlanDuration % 12 == 0) {
                $arrayKey = Str::slug($supplier . $PriceChangeDate . $PlanDuration . $PlanType);
                if (!key_exists($arrayKey, $output)) {
                    $output[$arrayKey] = [
                        'Duration' => $PlanDuration,
                        'PlanType' => $PlanType,
                        'Pricebook' => $plan['Pricebook'],
                        'Product' => $plan['Product'],
                        'Utility' => $plan['Utility'],
                        'HalfHourly' => $plan['HalfHourly'],
                        'IsRenewable' => $plan['IsRenewable'],
                    ];
                }
            }
        }
        return array_values($output);
    }

    public function getUplifts($supplier)
    {
        $list = [
            'british-gas' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'british-gas-lite' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'british-gas-plus' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'corona-energy' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => null]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => null]
                ]
            ],
            'drax' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'dyce-energy' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => 1]
                ],
                'gas' => [
                    1 => ['uplift' => 3, 'renewal' => 1]
                ]
            ],
            'e-on-next' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 1.5]
                ]
            ],
            'edf-energy' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2],
                    2 => ['uplift' => 2, 'renewal' => 2],
                    3 => ['uplift' => 2, 'renewal' => 2],
                    4 => ['uplift' => 2, 'renewal' => 2],
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2],
                    2 => ['uplift' => 2, 'renewal' => 2],
                    3 => ['uplift' => 2, 'renewal' => 2],
                    4 => ['uplift' => 2, 'renewal' => 2],
                ]
            ],
            'engie' => [
                'electric' => [
                    1 => ['uplift' => 4, 'renewal' => 1]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 1]
                ]
            ],
            'opus-energy' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'scottish-and-southern' => [
                'electric' => [
                    1 => ['uplift' => 2.5, 'renewal' => 2.5]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'scottish-power' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2],
                    2 => ['uplift' => 2, 'renewal' => 2],
                    3 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5],
                    2 => ['uplift' => 1.5, 'renewal' => 1.5],
                    3 => ['uplift' => 1.5, 'renewal' => 1.5],
                ]
            ],
            'sefe-energy' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'smartest-energy' => [
                'electric' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ],
                'gas' => [
                    1 => ['uplift' => 1, 'renewal' => 1]
                ]
            ],
            'totalenergies' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => 3]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ],
            'utilita' => [
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
            'valda-energy' => [
                'electric' => [
                    1 => ['uplift' => 2.5, 'renewal' => 2.5]
                ],
                'gas' => [
                    1 => ['uplift' => 1.5, 'renewal' => 1.5]
                ]
            ],
            'yorkshire-gas-and-power' => [
                'electric' => [
                    1 => ['uplift' => 3, 'renewal' => null]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => null]
                ]
            ],
            'yu-energy' => [
                'electric' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ],
                'gas' => [
                    1 => ['uplift' => 2, 'renewal' => 2]
                ]
            ]
        ];

        $supplier = Str::slug($supplier, '-');

        return $list[$supplier] ?? null;
    }
}
