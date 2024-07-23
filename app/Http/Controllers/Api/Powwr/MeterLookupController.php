<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\MeterLookup;
use App\Http\Integrations\Powwr\Requests\MeterLookupAddress;
use App\Http\Requests\Api\LoginRequest;
use App\Models\MeterExclusions;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @group Powwr
 * @unauthenticated
 */
class MeterLookupController extends ApiController
{

    /**
     * Meter Lookup
     *
     * Find address against postcode
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $request->validate([
            'postCode' => ['required', 'string'],
            'utilityType' => ['nullable', 'in:gas,electric'],
        ]);

        $utilityType = $request->utilityType;

        $cache_key = 'meter-lookup.' . Str::slug($request->postCode);

        $cachedAddresses = Cache::get($cache_key);

        if (!empty($cachedAddresses)) {

            // $output = $this->filterAddresses($cachedAddresses, $utilityType);

            //return response()->json(['success' => true, 'data' => $output]);
        }

        $connector = new MeterLookup();

        $address_request = new MeterLookupAddress($request->postCode);

        $address_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($address_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200) {
            if (app()->isProduction()) {
                unset($response_body['warnings']);
            }

            $addressesResult = $response_body['GetAddressesResult'] ?? [];

            if (!empty($addressesResult)) {
                Cache::put($cache_key, $addressesResult, (60 * 60 * 24));
            }

            $output = $this->filterAddresses($addressesResult, $utilityType);


            $responseData = ['success' => true, 'data' => $output];
        } else {
            $responseData = ['success' => false, 'data' => $response_body];
        }
        if (!app()->isProduction()) {
            $responseData['api_payload'] = $address_request->body()->all();
        }
        return response()->json($responseData);
    }

    protected function filterAddresses($addressesResult, $type)
    {
        if (isset($addressesResult['GetAddressesResult'])) {
            $addressesResult = $addressesResult['GetAddressesResult'];
        }

        $output = [];

        $addresses = $addressesResult['Addresses'] ?? [];
        foreach ($addresses as &$address) {
            $address = Arr::pluck($address, 'Value', 'Key');
            $address = array_change_key_case($address, CASE_LOWER);

            $MPANCore = $address['mpancore'] ?? '';
            $MPRN = $address['mprn'] ?? '';

            $serialNumbers = array_filter([
                $address['elecmeterserialnumber'] ?? '',
                $address['gasmeterserialnumber'] ?? '',
                $address['meterserialnumber'] ?? '',
            ]);

            $meterNumber = $MPRN ?: $MPANCore;
            $meterserialnumber = array_shift($serialNumbers);

            $row = [
                'meternumber' => $meterNumber,
                'meterserialnumber' => $meterserialnumber,
                'measurementclass' => $address['measurementclass'] ?? '',
                'metertype' => $address['metertype'] ?? '',
            ];

            if ($row['metertype'] == '@unknown') {
                $row['metertype'] = '';
            }

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

            $AddressAsLine = $address['addressasline'] ?? '';
            $AddressAsLine = str_replace('Unknown', '', $AddressAsLine);
            $AddressAsLine = preg_replace('/,+/', ', ', $AddressAsLine);
            //$AddressAsLine = preg_replace('/,([a-z0-9])/i', ', ', $AddressAsLine);
            $row['addressfull'] = $address['addressasline'] = trim($AddressAsLine, ', ');

            $row['exclusion'] = MeterExclusions::isExclusion($meterserialnumber);

            $addressline1 = $address['addressline1'] ?? '';
            $addressline2 = $address['addressline2'] ?? '';
            $addressline3 = $address['addressline3'] ?? '';
            $addressline4 = $address['addressline4'] ?? '';
            $addressline5 = $address['addressline5'] ?? '';
            $addressline6 = $address['addressline6'] ?? '';
            $addressline7 = $address['addressline7'] ?? '';
            $addressline8 = $address['addressline8'] ?? '';
            $addressline9 = $address['addressline9'] ?? '';

            $subbuilding = $address['subbuilding'] ?? $addressline3 ?? '';
            $buildingnumber = $address['buildingnumber'] ?? $addressline5 ?? '';
            $buildingname = $address['buildingname'] ?? $addressline1 ?? $addressline4;
            $thoroughfare = $address['thoroughfare'] ?? $addressline6;
            $posttown = $address['posttown'] ?? $addressline8;
            $county = $address['county'] ?? $addressline9;

            $address1 = [];
            if ($addressline1) {
                $address1[] = $addressline1;
            }
            if ($addressline2 && !in_array($addressline2, ['Unknown']) && !in_array($addressline2, $address1)) {
                $address1[] = $addressline2;
            }
            if ($addressline3 && !in_array($addressline3, ['Unknown']) && !in_array($addressline3, $address1)) {
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

            if ($thoroughfare && !in_array($thoroughfare, ['Unknown']) && !in_array($thoroughfare, $address1)) {
                $address1[] = $thoroughfare;
            }

            $address2 = [];
            if ($addressline4 && !in_array($addressline4, ['Unknown']) && !in_array($addressline4, $address1)) {
                $address2[] = $addressline4;
            }
            if ($addressline5 && !in_array($addressline5, ['Unknown']) && !in_array($addressline5, $address1) && !in_array($addressline5, $address2)) {
                $address2[] = $addressline5;
            }

            if ($addressline6 && !in_array($addressline6, ['Unknown']) && !in_array($addressline6, $address1) && !in_array($addressline6, $address2)) {
                $address2[] = $addressline6;
            }

            if ($thoroughfare && !in_array($thoroughfare, ['Unknown']) && !in_array($thoroughfare, $address1) && !in_array($thoroughfare, $address2)) {
                $address2[] = $thoroughfare;
            }
            if ($addressline7 && !in_array($addressline7, ['Unknown']) && !in_array($addressline7, $address1) && !in_array($addressline7, $address2)) {
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
            $row['postcode'] = $address['postcode'] ?? '';

            ksort($row);
            ksort($address);
            $row['raw'] = $address;

            $output[] = $row;
        }

        if (in_array($type, ['gas', 'electric'])) {
            $addresses = array_values(array_filter($output, function ($a) use ($type) {
                if (($type == 'gas' && array_key_exists('mprn', $a)) ||
                    ($type == 'electric' && array_key_exists('mpancore', $a))) {
                    return $a;
                }
            }));
        }

        return $addresses;
    }
}
