<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterExclusions extends Model
{
    use HasFactory;

    public static function isExclusion($serial_number, $meter_number = null, $mpr = null)
    {
        if (!$serial_number) {
            return false;
        }
        return MeterExclusions::where('serial_number', $serial_number)
            ->when($meter_number, function ($q, $meter_number) {
                $q->orWhere('meter_number', $meter_number);
            })->when($mpr, function ($q, $mpr) {
                $q->orWhere('mpr', $mpr);
            })
            ->exists();
    }
}
