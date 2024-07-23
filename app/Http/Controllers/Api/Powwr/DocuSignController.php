<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\ConnectApi;
use App\Http\Integrations\Powwr\Requests\AddDocuSignRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;

class DocuSignController extends ApiController
{

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'dealId' => ['required', 'string'],
            'recipientEmail' => ['required', 'email'],
        ]);

        $connector = new ConnectApi();

        $quote_request = new AddDocuSignRequest([
            'dealId' => $request->dealId,
            'recipientEmail' => $request->recipientEmail,
            'documentType' => ($request->documentType ?: 'Contract'),
        ]);

        $quote_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        $result = $connector->send($quote_request);

        $response_body = $result->body();
        if (isJson($response_body)) {
            $response_body = json_decode($response_body, true);
        }

        if ($result->status() == 200) {
            if (app()->isProduction()) {
                unset($response_body['warnings']);
            }
            return response()->json(['success' => true, 'data' => $response_body]);
        } else {
            return response()->json(['success' => false, 'data' => $response_body], 400);
        }
    }
}
