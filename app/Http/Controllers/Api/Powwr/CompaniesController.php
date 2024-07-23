<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\CompanyHouse;
use App\Http\Integrations\Powwr\Requests\CompanySearchRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @group Powwr
 * @unauthenticated
 */
class CompaniesController extends ApiController
{

    /**
     * Companies Search
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string'],
            'type' => ['nullable', 'string', 'in:ltd,plc,llp'],
        ]);

        $q = $request->q;
        $type = $request->type;
        $limit = $request->limit ?: 50;

        $cache_key = 'powwr-companies-search.' . $type . $limit . Str::slug($q);

        $cachedCompanies = Cache::get($cache_key);
        if (!empty($cachedCompanies)) {
            return response()->json([
                'success' => true,
                'data' => $cachedCompanies
            ]);
        }

        $connector = new CompanyHouse();

        $company_request = new CompanySearchRequest($q, $limit, $type);

        $company_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $apiResponse = $connector->send($company_request);

        $apiResponseBody = $apiResponse->body();
        if (isJson($apiResponseBody)) {
            $apiResponseBody = json_decode($apiResponseBody, true);
        }
        if ($apiResponse->status() == 200) {
            $result = $apiResponseBody['items'] ?? [];
            $hits = $apiResponseBody['hits'] ?? 0;

            $items = [];
            foreach ($result as $row) {
                $items[] = [
                    'title' => $row['company_name'] ?? $row['title'],
                    'number' => $row['company_number'] ?? '',
                    'type' => $row['company_type'] ?? '',
                    'date_creation' => $row['date_of_creation'] ?? '',
                    'date_cessation' => $row['date_of_cessation'] ?? '',
                    'description' => $row['description'] ?? '',
                    'status' => $row['company_status'] ?? '',
                    'address' => $row['registered_office_address'] ?? $row['address'] ?? '',
                    'sic_codes' => $row['sic_codes'] ?? '',
                    'raw' => $row,
                ];
            }
            if (!empty($items)) {
                Cache::put($cache_key, $items, (60 * 60 * 24));
            }
            $responseData = [
                'success' => true,
                'data' => $items,
                'total' => $hits
            ];
        } else {
            $responseData = [
                'success' => false,
                'data' => $apiResponseBody
            ];
        }

        if (!app()->isProduction()) {
            $responseData['api_payload'] = $company_request->query()->all();
        }

        return response()->json($responseData, $apiResponse->status());
    }
}
