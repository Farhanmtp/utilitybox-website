<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Schema::disableForeignKeyConstraints();

        // \App\Models\User::factory(10)->create();

        $this->call([
            SettingTableSeeder::class,
            SuppliersTable::class,
            RolesTableSeeder::class,
            UserTableSeeder::class,
            CategoryTableSeeder::class,
            PostsTableSeeder::class,
            PostcodesTable::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
