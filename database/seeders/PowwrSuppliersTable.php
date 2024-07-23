<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PowwrSuppliersTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('powwr_suppliers')->truncate();

        $file = fopen(base_path('database/PowwrSuppliers.csv'), 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            $SupplierName = $line[0] ?? '';
            $PowwrID = $line[1] ?? '';
            $Status = $line[2] ?? '';
            $Type = $line[3] ?? '';
            $logo = $line[4] ?? '';

            if ($SupplierName && !in_array(strtolower($PowwrID), ['powwrid', 'powwr id', 'powwr_id'])) {
                DB::table('powwr_suppliers')->insert([
                    'name' => $SupplierName,
                    'powwr_id' => $PowwrID,
                    'supplier_type' => $Type,
                    'status' => $Status ? 1 : 0,
                    'logo' => $logo && file_exists(public_path('images/logos/' . $logo)) ? 'images/logos/' . $logo : '',
                ]);
            }
        }
        fclose($file);
    }
}
