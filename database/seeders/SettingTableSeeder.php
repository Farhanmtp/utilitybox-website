<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'key' => 'app',
                'name' => 'Application Setting',
                'values' => '{"name":"Utility Box","logo":"images/logo.png","email":"hello@utilitybox.qrg.uk","phone":"+442039219000","address":"Utility Box Limited 429-433 Pinner Road, Harrow, HA1 4HN"}',
            ], [
                'id' => 2,
                'key' => 'mail',
                'name' => 'Mail Setting',
                'values' => '{"from_email":"no-reply@utilitybox.net","driver":"sendmail","timeout":"300"}',
            ], [
                'id' => 3,
                'key' => 'seo',
                'name' => 'SEO Setting',
                'values' => '{"facebook_link":"https:\/\/web.facebook.com\/profile.php?id=100086318885802","instagram_link":"https:\/\/www.instagram.com\/utilitybox.org.uk\/","twitter_link":"https:\/\/twitter.com\/UtilityBoxUk?t=gzrY0PuHtFAe_7cIZ7fS9A&s=09","linkedin_link":"https:\/\/www.linkedin.com\/company\/utility-box\/","robots_txt":"User-agent: *\r\nDisallow:\r\nsdsfsd dfadsf dafds"}',
            ]
        ];

        DB::table('settings')->truncate();

        DB::table('settings')->insert($items);
    }
}
