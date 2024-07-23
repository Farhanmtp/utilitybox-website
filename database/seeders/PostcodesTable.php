<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostcodesTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('postcodes')->truncate();

        $file = fopen(base_path('database/Postcodes.csv'), 'r');
        $count = 0;
        $insert = [];
        while (($line = fgetcsv($file)) !== FALSE) {
            $postcode = $line[0] ?? '';
            if ($postcode && !in_array(strtolower($postcode), ['postcode', 'post code', 'post_code'])) {
                $insert[] = ['postcode' => $postcode];
                if ($count > 0 && $count % 2000 == 0) {
                    DB::table('postcodes')->insert($insert);
                    $insert = [];
                }
                $count++;
            }
        }

        // insert if remaining record
        if (count($insert)) {
            DB::table('postcodes')->insert($insert);
        }

        fclose($file);
    }
}
