<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\ConnectApi;
use App\Http\Integrations\Powwr\Requests\SubmitQuoteRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;

/**
 * @group Powwr
 * @unauthenticated
 */
class QuoteController extends ApiController
{
    use BaseTrait;

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'dealId' => ['required', 'string'],
            'utilityType' => ['required', 'in:gas,electric'],
            'quotationType' => ['required', 'in:matrix,custom'],
        ]);

        /*$brokerage_id = config('powwr.brokerage_id');
        $brokerage_email = config('powwr.brokerage_email');
        $client_id = config('powwr.client_id');
        $client_secret = config('powwr.client_secret');
        $client_scope = config('powwr.client_scope') . ' brokerage:' . $brokerage_id;*/


        $connector = new ConnectApi();

        $quote_request = new SubmitQuoteRequest([
            'dealId' => $request->dealId,
            'utilityType' => ($request->utilityType == 'gas' ? 'SmeGas' : 'SmeElectricity'),
            'quotationType' => ($request->utilityType == 'matrix' ? 'Matrix ' : 'Custom'),
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
            $errors = data_get($response_body, 'extensions.errors');

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

        return response()->json($responseData, $result->status());
    }
}
