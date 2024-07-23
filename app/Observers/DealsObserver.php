<?php

namespace App\Observers;

use App\Models\Deals;
use App\Models\User;
use App\Notifications\Admin\DealCreatedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DealsObserver
{
    /**
     * Handle the Deals "saved" event.
     */
    public function saving(Deals $deal): void
    {
        $attributes = $deal->getAttributes();

        if (data_get($attributes, 'currentSupplier') != data_get($deal, 'contract.currentSupplier')) {
            $deal->currentSupplier = data_get($deal, 'contract.currentSupplier');
        }
        if (data_get($attributes, 'currentSupplier') != data_get($deal, 'contract.newSupplier')) {
            $deal->newSupplier = data_get($deal, 'contract.newSupplier');
        }
        $deal->customer_email = data_get($deal, 'customer.email');
        $deal->customer_phone = data_get($deal, 'customer.phone');
        $deal->customer_name = trim(data_get($deal, 'customer.firstName') . ' ' . data_get($deal, 'customer.lastName'));
    }

    /**
     * finalized
     * @param Deals $deal
     * @return void
     */
    public function updated(Deals $deal)
    {
        $old_status = $deal->getOriginal('status');

        if ($old_status && $old_status == 'pending' && $deal->status == 'action-required') {
            $email = settings('app.notification-email');
            $newSupplier = data_get($deal, 'contract.newSupplier');
            if ($email && in_array(strtolower($newSupplier), ['e-on next', 'e-on'])) {
                Notification::route('mail', $email)->notify(new DealCreatedNotification($deal));
            }
        }
    }

    public function created(Deals $deal)
    {

    }

    /**
     * Handle the Deals "saved" event.
     */
    public function saved(Deals $deal): void
    {
        if (is_null($deal->link_sent_at) && !auth()->check() && $deal->customer_email) {
            $user = User::where('email', $deal->customer_email)->first();
            if (!$user) {
                $user = User::create([
                    'email' => $deal->customer_email,
                    'first_name' => $deal->customer_name
                ]);
            }

            if ($user) {
                if (!$deal->user_id) {
                    DB::table('deals')->where('id', $deal->id)->update(['user_id' => $user->id]);
                }

                if (!$user->email_verified_at) {
                    $user->sendEmailVerificationNotification($deal);
                } else {
                    $user->notify(new \App\Notifications\DealCreatedNotification($deal));
                }

                DB::table('deals')->where('id', $deal->id)->update(['link_sent_at' => Carbon::now()]);
            }
        }
    }
}
