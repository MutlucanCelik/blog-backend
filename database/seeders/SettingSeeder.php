<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
           'logo_image' => '',
           'footer_text' => 'footer text',
           'description' => 'Açıklama',
           'home_slider_image' => '',
           'home_slider_text' => 'home slider text',

        ]);
    }
}
