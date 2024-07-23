<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MeterExclusionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('meter_exclusions')->truncate();

        $file = fopen(base_path('database/meterExclusions.csv'), 'r');
        $count = 0;
        $insert = [];

        while (($line = fgetcsv($file)) !== FALSE) {
            $utility_type = $line[0] ?? '';
            $meter_number = $line[1] ?? '';
            $mpan_top = $line[2] ?? '';
            $mpr = $line[3] ?? '';
            $serial_number = $line[4] ?? '';

            if (Str::contains($utility_type, ['utility_type', 'supply', 'type'])) {
                continue;
            }

            if (!$meter_number) {
                $meter_number = $mpr;
                $mpr = null;
            }

            $data = [
                'utility_type' => $utility_type ?: null,
                'meter_number' => $meter_number ?: null,
                'mpan_top' => $mpan_top ?: null,
                'mpr' => $mpr ?: null,
                'serial_number' => $serial_number ?: null,
            ];

            if (!empty(array_filter($data))) {
                $insert[] = $data;
                if ($count > 0 && $count % 1000 == 0) {
                    DB::table('meter_exclusions')->insert($insert);
                    $insert = [];
                }
                $count++;
            }
        }

        // insert if remaining record
        if (count($insert)) {
            DB::table('meter_exclusions')->insert($insert);
        }

        fclose($file);
    }
}
