<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\Requests\GetPromptsRequest;
use App\Http\Integrations\Powwr\UdCoreApiConnector;
use App\Http\Requests\Api\LoginRequest;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @group Powwr
 * @unauthenticated
 */
class GetPromptController extends ApiController
{

    /**
     * Get Prompts
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'meterNumber' => ['required', 'string'],
            'mpanTop' => ['required', 'string'],
        ]);

        set_time_limit(1000);
        ini_set('max_execution_time', 1000);

        $cache_key = 'meter-lookup.' . Str::slug($request->meterNumber) . '-' . $request->mpanTop;

        $cachedResult = Cache::get($cache_key);
        if (!empty($cachedResult)) {
            return response()->json(['success' => true, 'data' => $cachedResult]);
        }

        $request_data = $this->getBody($request);

        $connector = new UdCoreApiConnector();

        $api_request = new GetPromptsRequest($request_data);

        $api_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $apiResponse = $connector->send($api_request);

        $apiResponseBody = $apiResponse->body();
        if (isJson($apiResponseBody)) {
            $apiResponseBody = json_decode($apiResponseBody, true);
        }

        if ($apiResponse->status() == 200) {
            $result = $apiResponseBody['GetNumberOfPromptsResult'] ?? [];

            if (!empty($result)) {
                Cache::put($cache_key, $result, (60 * 60 * 24));
            }

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

        if (!app()->isProduction()) {
            $responseData['api_payload'] = $api_request->body()->all();
        }

        return response()->json($responseData, $apiResponse->status());
    }

    protected function getBody(Request $request)
    {
        $suppliers = Suppliers::apiSuppliers();

        $promptsSearch = [
            'MPANBottom' => $request->meterNumber,
            'MPANTop' => $request->mpanTop,
            'Suppliers' => array_keys($suppliers)
        ];
        return $promptsSearch;
    }
}
