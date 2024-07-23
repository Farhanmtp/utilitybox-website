<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\PowwrSearchApiConnector;
use App\Http\Integrations\Powwr\Requests\PowwrMeterLookupRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\MeterExclusions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @group Powwr
 * @unauthenticated
 */
class PowwrMeterLookupController extends ApiController
{

    /**
     * Meter Lookup
     *
     * Find address against postcode or meter number or address id
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam postCode string required Set postcode, meter number or address id
     */
    public function index(Request $request)
    {
        $request->validate([
            'postCode' => ['required', 'string'],
            'utilityType' => ['nullable', 'in:gas,electric'],
        ]);

        $utilityType = $request->utilityType;
        $postCode = $request->postCode;

        $type = 'postcode';

        if (is_numeric($postCode)) {
            $type = 'number';
        }
        if (strlen($postCode) > 22) {
            $type = 'id';
        }

        $cache_key = 'powwr-meter-lookup.' . $type . '-' . Str::slug($request->postCode);

        $cachedAddresses = Cache::get($cache_key);

        if (!empty($cachedAddresses)) {
            //return response()->json(['success' => true, 'data' => $cachedAddresses]);
        }

        $connector = new PowwrSearchApiConnector();

        $address_request = new PowwrMeterLookupRequest($postCode, $type);
        $result = $connector->send($address_request);
        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200) {

            if ($type == 'postcode') {
                $addressesResult = $response_body['meterAddresses'] ?? [];
                //dd($addressesResult);
                $output = [];
                foreach ($addressesResult as $address) {
                    $meters = $address['Mpans'] ?? $address['mpans'] ?? [];
                    if ($utilityType == 'gas') {
                        $meters = $address['Mprns'] ?? $address['mprns'] ?? [];
                    }
                    if (!empty($meters)) {
                        foreach ($meters as $meter) {
                            $output[] = [
                                'id' => $address['meterAddressId'] ?? $address['MeterAddressId'] ?? '',
                                'fulladdress' => $address['fullAddress'] ?? $address['FullAddress'] ?? '',
                                'meternumber' => $meter,
                                'type' => 'id',
                                'raw' => $address
                            ];
                        }
                    }
                }
            } else {
                $output = $this->filterAddresses($response_body, $utilityType);
            }

            if (!empty($output)) {
                Cache::put($cache_key, $output, (60 * 60 * 24));
            }

            $responseData = ['success' => true, 'data' => $output, 'type' => $type];
        } else {
            $responseData = ['success' => false, 'data' => $response_body, 'type' => $type];
        }
        if (!app()->isProduction()) {
            $responseData['api_payload'] = $address_request->body()->all();
        }
        return response()->json($responseData);
    }

    protected function filterAddresses($addressesResult, $type)
    {
        $output = [];

        if ($type == 'electric') {
            $addresses = $addressesResult['ElectricMeters'] ?? $addressesResult['electricMeters'] ?? [];
        } else {
            $addresses = $addressesResult['GasMeters'] ?? $addressesResult['gasMeters'] ?? [];
        }

        foreach ($addresses as &$address) {
            $address = array_change_key_case($address, CASE_LOWER);

            $MPAN = $address['mpan'] ?? '';
            $MPRN = $address['mprn'] ?? '';

            $serialNumbers = array_filter([
                $address['elecmeterserialnumber'] ?? '',
                $address['gasmeterserialnumber'] ?? '',
                $address['meterserialnumber'] ?? '',
                $address['serialnumber'] ?? '',
            ]);

            $meterPointLocation = array_change_key_case($address['meterpointlocation'], CASE_LOWER);

            $meterNumber = $MPRN ?: $MPAN;
            $meterserialnumber = array_shift($serialNumbers);
            if (!$meterserialnumber) {
                //continue;
            }

            $row = [
                'meternumber' => $meterNumber,
                'meterserialnumber' => $meterserialnumber,
                'measurementclass' => $address['measurementclass'] ?? '',
                'metertype' => $address['metertype'] ?? '',
            ];

            if (isset($address['mprn'])) {
                $row['mprn'] = $address['mprn'];
            }
            if (isset($address['mpancore'])) {
                $row['mpancore'] = $address['mpancore'];
            }

            if ($type == 'electric') {
                $num1 = $address['profileclass'] ?? '';
                $num2 = $address['metertimeswitchclass'] ?? '';
                $num3 = $address['linelossfactor'] ?? '';

                $row['mpantop'] = $num1 . $num2 . $num3;
                $metertype = '';
                $pc = (int)$num1;
                if ($pc == 1 || $pc == 2) {
                    $metertype = 'domestic'; //is domestic
                }
                if ($pc == 3 || $pc == 4) {
                    $metertype = 'small-business'; //is small business
                }
                if ($pc >= 5 && $pc <= 8) {
                    $metertype = 'commercial'; //is large commercial
                }
                if ($pc == 0) {
                    $metertype = 'half-hourly'; //is halfhourly
                }
                $row['profile'] = $metertype;
            }
            $AddressAsLine = $meterPointLocation['fulladdress'] ?? '';
            $AddressAsLine = preg_replace('/,+/', ', ', $AddressAsLine);
            //$AddressAsLine = preg_replace('/,([a-z0-9])/i', ', ', $AddressAsLine);
            $row['fulladdress'] = $meterPointLocation['fulladdress'] = trim($AddressAsLine, ', ');

            $row['exclusion'] = MeterExclusions::isExclusion($meterserialnumber);

            $addressline1 = $meterPointLocation['addressline1'] ?? '';
            $addressline2 = $meterPointLocation['addressline2'] ?? '';
            $addressline3 = $meterPointLocation['addressline3'] ?? '';
            $addressline4 = $meterPointLocation['addressline4'] ?? '';
            $addressline5 = $meterPointLocation['addressline5'] ?? '';
            $addressline6 = $meterPointLocation['addressline6'] ?? '';
            $addressline7 = $meterPointLocation['addressline7'] ?? '';
            $addressline8 = $meterPointLocation['addressline8'] ?? '';
            $addressline9 = $meterPointLocation['addressline9'] ?? '';

            $subbuilding = $meterPointLocation['subbuilding'] ?? $addressline3 ?? '';
            $buildingnumber = $meterPointLocation['buildingnumber'] ?? $addressline1 ?? '';
            $buildingname = $meterPointLocation['buildingname'] ?? $addressline4;
            $thoroughfare = $meterPointLocation['thoroughfare'] ?? $addressline6;
            $posttown = $meterPointLocation['posttown'] ?? $addressline8;
            $county = $meterPointLocation['county'] ?? $addressline9;
            $postcode = $meterPointLocation['postcode'] ?? '';

            $address1 = [];
            if ($addressline1) {
                $address1[] = $addressline1;
            }
            if ($addressline2 && !in_array($addressline2, $address1)) {
                $address1[] = $addressline2;
            }
            if ($addressline3 && !in_array($addressline3, $address1)) {
                $address1[] = $addressline3;
            }
            if ($subbuilding && !in_array($subbuilding, $address1) &&
                !in_array($subbuilding, [$addressline4, $addressline5, $addressline6, $addressline7])) {
                $address1[] = $subbuilding;
            }

            if ($buildingnumber && !in_array($buildingnumber, $address1) &&
                !in_array($buildingnumber, [$addressline3, $addressline6, $addressline7])) {
                $address1[] = $buildingnumber;
            }

            if ($buildingname && !in_array($buildingname, $address1) &&
                !in_array($buildingname, [$addressline3, $addressline5, $addressline6, $addressline7])) {
                $address1[] = $buildingname;
            }
            if ($thoroughfare && !in_array($thoroughfare, $address1)) {
                $address1[] = $thoroughfare;
            }

            $address2 = [];
            if ($addressline4 && !in_array($addressline4, $address1)) {
                $address2[] = $addressline4;
            }
            if ($addressline5 && !in_array($addressline5, $address1) && !in_array($addressline5, $address2)) {
                $address2[] = $addressline5;
            }

            if ($addressline6 && !in_array($addressline6, $address1) && !in_array($addressline6, $address2)) {
                $address2[] = $addressline6;
            }

            if ($thoroughfare && !in_array($thoroughfare, $address1) && !in_array($thoroughfare, $address2)) {
                $address2[] = $thoroughfare;
            }
            if ($addressline7 && !in_array($addressline7, $address1) && !in_array($addressline7, $address2)) {
                $address2[] = $addressline7;
            }

            $_address1 = implode(' ', $address1);
            $_address2 = implode(' ', $address2);
            if (!$_address1) {
                $first = substr($_address2, 0, strpos($_address2, ' '));
                if (is_numeric($first[0])) {
                    $_address1 = trim($first);
                    $_address2 = trim(substr($_address2, strlen($first)));
                }
            }

            $row['addressline1'] = $_address1;
            $row['addressline2'] = $_address2;
            $row['posttown'] = $posttown;

            $row['county'] = $county;
            $row['postcode'] = $postcode;

            ksort($row);
            ksort($address);
            $row['raw'] = $address;

            $output[] = $row;
        }
        return $output;
    }
}
