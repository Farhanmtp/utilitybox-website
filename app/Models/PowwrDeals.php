<?php

namespace App\Models;

use App\Models\Traits\ContractTrait;
use App\Models\Traits\DealTrait;
use App\Notifications\EmailVerificationNotification;
use App\Observers\PowwrDealsObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * @property int $user_id
 * @property string|null $dealId
 * @property string|null $supplierId
 * @property string|null $utilityType
 * @property string|null $meterNumber
 * @property array|null $customer
 * @property array|null $customerAddress
 * @property array|null $customerCompany
 * @property string|null $site
 * @property array|null $siteContact
 * @property array|null $siteAddress
 * @property array|null $contractDetails
 * @property array|null $smeDetails
 */
class PowwrDeals extends Model
{
    use HasFactory, DealTrait, ContractTrait, Notifiable;

    protected $fillable = [
        'user_id',
        'dealId',
        'supplierId',
        'utilityType',
        'meterNumber',
        'customer_name',
        'customer_email',
        'customer_phone',
        'step',
        'tab',
        'customer',
        'company',
        'site',
        'contract',
        'smeDetails',
        'billingAddress',
        'paymentDetail',
        'bankDetails',
        'bankAddress',
        'quoteDetails',
        'consents',
        'rates',
        'usage',
        'status',
    ];

    protected $casts = [
        'step' => 'int',
        'customer' => 'array',
        'company' => 'array',
        'site' => 'array',
        'contract' => 'array',
        'smeDetails' => 'array',
        'billingAddress' => 'array',
        'paymentDetail' => 'array',
        'bankDetails' => 'array',
        'bankAddress' => 'array',
        'quoteDetails' => 'array',
        'consents' => 'array',
        'rates' => 'array',
        'usage' => 'array',
    ];


    protected static function boot()
    {
        parent::boot();

        PowwrDeals::observe(PowwrDealsObserver::class);
    }


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(PowwrSupplier::class, 'supplierId', 'powwr_id');
    }

    protected function customer(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->customer));
            }
        );
    }

    protected function company(): Attribute
    {
        return Attribute::make(
            set: function ($value) {

                $type = strtolower(data_get($value, 'type'));
                if ($type == 'ltd') {
                    data_set($value, 'type', 'Limited');
                }
                if ($type == 'plc') {
                    data_set($value, 'type', 'PLC');
                }
                if ($type == 'llp') {
                    data_set($value, 'type', 'LimitedLiabilityPartnership');
                }

                return json_encode($this->merge_values($value, $this->company));
            }
        );
    }

    protected function site(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->site));
            }
        );
    }

    protected function contract(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->contract));
            }
        );
    }

    protected function smeDetails(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->smeDetails));
            }
        );
    }

    protected function billingAddress(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->billingAddress));
            }
        );
    }

    protected function paymentDetail(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $newSupplier = data_get($this, 'contract.newSupplier');
                if (Str::startsWith($newSupplier, 'BRITISHG-002')) {
                    data_set($value, 'method', 'VariableDirectDebit');
                }
                return json_encode($this->merge_values($value, $this->paymentDetail));
            }
        );
    }

    protected function bankDetails(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->bankDetails));
            }
        );
    }

    protected function bankAddress(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->bankAddress));
            }
        );
    }

    protected function consents(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->consents));
            }
        );
    }

    protected function rates(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->rates));
            }
        );
    }

    protected function usage(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return json_encode($this->merge_values($value, $this->usage));
            }
        );
    }

    /**
     * @return void
     */
    public function sendDealLinkNotification(): void
    {
        $email = $this->customer_email;
        if ($this->user) {
            $email = $this->user->email;
        }

        if ($email) {
            try {
                Notification::route('mail', $email)->notify(new EmailVerificationNotification($email, $this));
            } catch (\Exception $e){

            }

            $this->link_sent_at = Carbon::now();
            $this->save();
        }
    }

    protected function merge_values($value, $old)
    {
        if (is_array($value)) {
            if (isJson($old)) {
                $old = json_decode($old, true);
            }
            if (is_array($old)) {
                $value = array_merge_recursive_distinct($old, $value);
            }
            return array_filter_recursive($value, function ($a) {
                return !is_null($a);
            });
        } else {
            return $old;
        }
    }
}
