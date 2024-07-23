<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Integrations\Powwr\ConnectApi;
use App\Http\Integrations\Powwr\Requests\AddDocumentRequest;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends ApiController
{

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $documentTypes = [
            'ElecSignedContract',
            'ElecVerbalContractRecording',
            'ElecVerbalContractScript',
            'GasSignedContract',
            'GasVerbalContractRecording',
            'GasVerbalContractScript',
            'DualFuelSignedContract',
            'DualFuelVerbalContractRecording',
            'DualFuelVerbalContractScript',
            'Loa',
            'General',
            'VatExemption',
            'CclExemption',
            'DdMandate',
            'ProofOfChangeOfTenancy',
            'TaxExemptionDocumentation',
            'ProofOfPayment',
            'ProofOfAddress',
            'ProofOfTrading',
            'ProofOfBusiness',
            'VatDeclarationForm'
        ];

        $request->validate([
            'dealId' => ['required', 'string'],
            'documentType' => ['required', Rule::in($documentTypes)],
            'document' => ['required', 'file'],
        ]);

        $document = $request->file('document');

        $documentName = $document->getClientOriginalName();
        $documentContent = base64_encode($document->getContent());

        $connector = new ConnectApi();

        $document_request = new AddDocumentRequest([
            'dealId' => $request->dealId,
            "replaceExisting" => true,
            "documentName" => $documentName,
            "documentContent" => $documentContent,
            "documentType" => $request->documentType
        ]);

        $document_request->body()->setJsonFlags(JSON_FORCE_OBJECT);

        //dd($deal_request->body());

        $result = $connector->send($document_request);

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
