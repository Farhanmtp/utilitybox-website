<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'hashId' => md5($this->id),
            'dealId' => $this->dealId,
            'envelopeId' => $this->envelopeId,
            'loaEnvelopeId' => $this->loaEnvelopeId,
            'step' => $this->step,
            'tab' => $this->tab,
            'supplierId' => $this->supplierId,
            'utilityType' => $this->utilityType,
            'meterNumber' => $this->meterNumber,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'customer' => $this->customer,
            'company' => $this->company,
            'site' => $this->site,
            'contract' => $this->contract,
            'smeDetails' => $this->smeDetails,
            'billingAddress' => $this->billingAddress,
            'paymentDetail' => $this->paymentDetail,
            'bankDetails' => $this->bankDetails,
            'bankAddress' => $this->bankAddress,
            'quoteDetails' => $this->quoteDetails,
            'consents' => $this->consents,
            'rates' => $this->rates,
            'usage' => $this->usage,
            'status' => $this->status,
            'user' => UserResource::make($this->whenLoaded('user')),
            'supplier' => SupplierResource::make($this->whenLoaded('supplier')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
