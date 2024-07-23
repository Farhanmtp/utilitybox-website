<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\DealResource;
use App\Models\Deals;
use Illuminate\Http\Request;

/**
 * @group Powwr
 * @unauthenticated
 */
class DealsController extends ApiController
{

    use BaseTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveData(Request $request)
    {

        $id = $request->get('id');

        $tab = $request->get('tab');
        $step = $request->get('step');

        if ($id) {
            $deal = Deals::firstOrNew(['id' => $id]);
        } else {
            $deal = new Deals();
        }

        $deal->supplierId = $request->supplierId;

        if (!$deal->user_id && $userId = $request->input('user_id', auth()->id())) {
            $deal->user_id = $userId;
        }

        if ($request->exists('step')) {
            $deal->step = $request->step;
        }
        if ($request->exists('tab')) {
            $deal->tab = $request->tab;
        }

        if ($request->exists('dealId')) {
            $deal->dealId = $request->dealId;
        }

        if ($request->exists('utilityType')) {
            $deal->utilityType = $request->utilityType;
        }

        if ($request->exists('meterNumber')) {
            $deal->meterNumber = $request->meterNumber;
        }

        if ($request->exists('customer')) {
            $deal->customer = $request->customer;
        }

        if ($request->exists('company')) {
            $company = $request->company;
            $type = data_get($company, 'type');
            if ($type == 'ltd') {
                data_set($company, 'type', 'Limited');
            }
            if ($type == 'plc') {
                data_set($company, 'type', 'PLC');
            }
            $deal->company = $request->company;
        }

        if ($request->exists('site')) {
            $deal->site = $request->input('site');
        }
        if ($request->exists('smeDetails')) {
            $deal->smeDetails = $request->input('smeDetails');
        }

        if ($request->exists('contract')) {
            $deal->contract = $request->input('contract');
        }
        if ($request->exists('billingAddress')) {
            $deal->billingAddress = $request->input('billingAddress');
        }

        if ($request->exists('paymentDetail')) {
            $deal->paymentDetail = $request->input('paymentDetail');
        }

        if ($request->exists('bankDetails')) {
            $deal->bankDetails = $request->input('bankDetails');
        }

        if ($request->exists('bankAddress')) {
            $deal->bankAddress = $request->input('bankAddress');
        }

        if ($request->exists('quoteDetails')) {
            $deal->quoteDetails = $request->input('quoteDetails');
        }
        if ($request->exists('consents')) {
            $deal->consents = $request->input('consents');
        }

        if ($request->exists('rates')) {
            $deal->rates = $request->input('rates');
        }

        if ($request->exists('usage')) {
            $deal->usage = $request->input('usage');
        }

        $deal->save();

        if ($deal->id) {
            $save_deal_response = null;
            /*if ($step > 4) {
                try {
                    $save_deal_response = $deal->saveDeal();
                    if ($save_deal_response['success']) {
                        $dealId = $save_deal_response['dealId'];
                        if ($dealId) {
                            $deal->dealId = $dealId;
                        }
                    }
                } catch (\Exception) {
                }
            }*/
            if ($request->tab == 'finalize') {
                //SendDocuSign::dispatch($deal);
                $supplier = data_get($deal, 'contract.newSupplier');
                $deal->status = 'finalized';
                if (in_array(strtolower($supplier), ['e-on next'])) {
                    $deal->status = 'action-required';
                }

                try {
                    $loaResponse = $deal->sendLoa($deal);
                    if ($loaResponse['success']) {
                        $loaEnvelopeId = $loaResponse['envelopeId'] ?? '';
                        if ($loaEnvelopeId) {
                            $deal->loaEnvelopeId = $loaEnvelopeId;
                        }
                    }
                } catch (\Exception $e) {
                }
                try {
                    $response = $deal->sendDocuSign($deal);
                    if ($response['success']) {
                        $envelopeId = $response['envelopeId'] ?? '';
                        if ($envelopeId) {
                            $deal->envelopeId = $envelopeId;
                        }
                    }
                } catch (\Exception $e) {
                }
                $deal->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully.',
                'data' => DealResource::make($deal),
                'deal_resp' => $save_deal_response
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Data not saved.']);
    }

    public function saveDeal(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $deal = Deals::where('dealId', $request->id)->first();
        if ($deal) {
            $response = $deal->saveDeal();
            return response()->json($response);
        }

        return response()->json(['success' => false]);
    }

    public function read(Request $request)
    {

        $request->validate([
            'dealId' => 'required',
        ]);

        $deal = Deals::where('dealId', $request->dealId)->first();
        if (!$deal) {
            return response()->json(['success', false, 'error' => 'Deal not found.']);
        }

        return response()->json(['success' => true, 'data' => $deal]);
    }
}
