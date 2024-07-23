<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = [
            [
                'title' => 'General',
                'slug' => 'general',
                'status' => 1
            ], [
                'title' => 'Featured',
                'slug' => 'featured',
                'status' => 1
            ]
        ];


        Schema::disableForeignKeyConstraints();

        DB::table('categories')->truncate();
        DB::table('categories')->insert($categories);
        Schema::enableForeignKeyConstraints();
    }
}
