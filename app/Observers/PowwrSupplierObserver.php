<?php

namespace App\Observers;

use App\Models\PowwrSupplier;
use Illuminate\Support\Facades\Storage;

class PowwrSupplierObserver
{
    /**
     * Handle the PowwrSupplier "created" event.
     */
    public function created(PowwrSupplier $supplier): void
    {
        // ...
    }

    /**
     * Handle the PowwrSupplier "created" event.
     */
    public function deleting(PowwrSupplier $supplier): void
    {
        try {
            $logo = $supplier->logo;
            if (Storage::exists($logo)) {
                Storage::delete($logo);
            }
        } catch (\Exception $e) {
            alert_message($e->getMessage());
        }
    }
}
