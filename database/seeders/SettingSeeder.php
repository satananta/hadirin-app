<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00',
            'max_jam_masuk' => '09:00:00',
            'lat' => -6.234524486140895,
            'long' => 106.74744433993867,
            'radius' => 140
        ]);
    }
}
