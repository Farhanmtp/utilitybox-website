<?php

namespace App\Http\Controllers\Api\Powwr;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\DealResource;
use App\Jobs\SendDocuSign;
use App\Models\PowwrDeals;
use Illuminate\Http\Request;

class DealsController extends ApiController
{

    use BaseTrait;

    public function saveData(Request $request)
    {

        $id = $request->get('id');

        $tab = $request->get('tab');
        $step = $request->get('step');

        if ($id) {
            $deal = PowwrDeals::firstOrNew(['id' => $id]);
        } else {
            $deal = new PowwrDeals();
        }

        $deal->supplierId = $request->supplierId;

        if ($userId = $request->input('user_id')) {
            $deal->user_id = $userId;
        }

        if ($request->exists('step')) {
            $deal->step = $request->step;
        }
        if ($request->exists('tab')) {
            $deal->tab = $request->tab;
        }

        if ($request->exists('userId')) {
            $deal->user_id = $request->userId;
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
            $deal->customer = array_filter($request->customer);
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
            $deal->company = array_filter($request->company);
        }

        if ($request->exists('site')) {
            $deal->site = array_filter($request->input('site'));
        }
        if ($request->exists('smeDetails')) {
            $deal->smeDetails = array_filter($request->input('smeDetails'));
        }

        if ($request->exists('contract')) {
            $deal->contract = array_filter($request->input('contract'));
        }
        if ($request->exists('billingAddress')) {
            $deal->billingAddress = array_filter($request->input('billingAddress'));
        }

        if ($request->exists('paymentDetail')) {
            $deal->paymentDetail = array_filter($request->input('paymentDetail'));
        }

        if ($request->exists('bankDetails')) {
            $deal->bankDetails = array_filter($request->input('bankDetails'));
        }

        if ($request->exists('bankAddress')) {
            $deal->bankAddress = array_filter($request->input('bankAddress'));
        }

        if ($request->exists('quoteDetails')) {
            $deal->quoteDetails = $request->input('quoteDetails');
        }
        if ($request->exists('consents')) {
            $deal->consents = $request->input('consents');
        }

        if ($request->exists('rates')) {
            $deal->rates = array_filter($request->input('rates'));
        }

        if ($request->exists('usage')) {
            $deal->usage = array_filter($request->input('usage'));
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
                try {
                    $loaResponse = $deal->sendLoa($deal);
                    if ($loaResponse['success']) {
                        $loaEnvelopeId = $loaResponse['envelopeId'] ?? '';
                        if ($loaEnvelopeId) {
                            $deal->loaEnvelopeId = $loaEnvelopeId;
                            $deal->save();
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
                            $deal->save();
                        }
                    }
                } catch (\Exception $e) {
                }


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

        $deal = PowwrDeals::where('dealId', $request->id)->first();
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

        $deal = PowwrDeals::where('dealId', $request->dealId)->first();
        if (!$deal) {
            return response()->json(['success', false, 'error' => 'Deal not found.']);
        }

        return response()->json(['success' => true, 'data' => $deal]);
    }
}
