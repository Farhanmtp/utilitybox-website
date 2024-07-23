<?php

namespace App\Observers;

use App\Models\Suppliers;
use Illuminate\Support\Facades\Storage;

class SupplierObserver
{
    /**
     * Handle the Suppliers "created" event.
     */
    public function created(Suppliers $supplier): void
    {
        // ...
    }

    /**
     * Handle the Suppliers "created" event.
     */
    public function deleting(Suppliers $supplier): void
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
