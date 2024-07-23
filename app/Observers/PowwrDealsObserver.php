<?php

namespace App\Observers;

use App\Models\PowwrDeals;
use App\Models\User;

class PowwrDealsObserver
{
    /**
     * Handle the PowwrDeals "saved" event.
     */
    public function saving(PowwrDeals $deal): void
    {
        $deal->customer_email = data_get($deal, 'customer.email');
        $deal->customer_phone = data_get($deal, 'customer.phone');
        $deal->customer_name = trim(data_get($deal, 'customer.firstName') . ' ' . data_get($deal, 'customer.lastName'));
    }

    /**
     * Handle the PowwrDeals "saved" event.
     */
    public function saved(PowwrDeals $deal): void
    {
        if (is_null($deal->link_sent_at)) {
            $user = User::where('email', $deal->customer_email)->exists();
            if (!auth()->check() || !$user) {
                $deal->sendDealLinkNotification();
            }
        }
    }
}
