<?php

namespace App\Models;

use App\Models\Traits\ContractTrait;
use App\Models\Traits\DealTrait;
use App\Notifications\DealEmailVerificationNotification;
use App\Observers\DealsObserver;
use App\Traits\UtilsTrait;
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
class Deals extends Model
{
    use HasFactory, DealTrait, ContractTrait, Notifiable, UtilsTrait;

    protected $fillable = [
        'user_id',
        'dealId',
        'supplierId',
        'utilityType',
        'meterNumber',
        'customUplift',
        'upliftSupplier',
        'currentSupplier',
        'newSupplier',
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
        'allowedSuppliers',
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
        'allowedSuppliers' => 'array',
        'consents' => 'array',
        'rates' => 'array',
        'usage' => 'array',
        'created_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        Deals::observe(DealsObserver::class);
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
        return $this->belongsTo(Suppliers::class, 'supplierId', 'powwr_id');
    }

    protected function currentSupplier(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return $value ?: data_get($this, 'contract.currentSupplier');
            },
            get: function ($value) {
                return $value ?: data_get($this, 'contract.currentSupplier');
            }
        );
    }

    protected function newSupplier(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return $value ?: data_get($this, 'contract.newSupplier');
            },
            get: function ($value) {
                return $value ?: data_get($this, 'contract.newSupplier');
            }
        );
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
            get: function ($value) {
                if (isJson($value)) {
                    $value = json_decode($value, true);
                }
                if (isset($value['currentSupplierName'])) {
                    $value['currentSupplier'] = $value['currentSupplierName'];
                }
                if (isset($value['newSupplierName'])) {
                    $value['newSupplier'] = $value['newSupplierName'];
                }
                return $value;
            },
            set: function ($value) {

                $value = $this->merge_values($value, $this->contract);

                if (isset($value['currentSupplier']) && $value['currentSupplier']) {
                    unset($value['currentSupplierName']);
                }
                if (isset($value['newSupplier']) && $value['newSupplier']) {
                    unset($value['newSupplierName']);
                }

                return json_encode($value);
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

    public function getStatusHtmlAttribute()
    {
        if ($this->status == 'action-required') {
            return '<span class="badge badge-danger">Action Required</span>';
        }
        if ($this->status == 'pending') {
            return '<span class="badge badge-warning">Pending</span>';
        }
        return '<span class="badge badge-success">' . ucfirst($this->status) . '</span>';
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
                Notification::route('mail', $email)->notify(new DealEmailVerificationNotification($email, $this));
            } catch (\Exception $e) {

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
