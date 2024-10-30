<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Absen;
use App\Models\User;

class AbsenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Absen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lat' => $this->faker->latitude,
            'long' => $this->faker->randomFloat(6, 0, 999.999999),
            'lokasi' => $this->faker->text,
            'jam_datang' => $this->faker->time(),
            'jam_pulang' => $this->faker->time(),
            'tanggal_absen' => $this->faker->date(),
            'kategori' => $this->faker->randomElement(["Terlambat","Tepat Waktu","Izin","Cuti"]),
            'status' => $this->faker->randomElement(["Hadir","Tidak Hadir"]),
            'user_id' => User::factory(),
        ];
    }
}
