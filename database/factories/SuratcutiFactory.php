<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Suratcuti;
use App\Models\User;

class SuratcutiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Suratcuti::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'keterangan' => $this->faker->text,
            'keterangan_admin' => $this->faker->text,
            'tanggal_awal' => $this->faker->date(),
            'tanggal_akhir' => $this->faker->date(),
            'status' => $this->faker->randomElement(["Pending","Terima","Tolak"]),
            'user_id' => User::factory(),
        ];
    }
}
