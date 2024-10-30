<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Suratizin;
use App\Models\User;

class SuratizinFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Suratizin::class;

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
            'file_izin' => $this->faker->text,
            'tanggal_izin' => $this->faker->date(),
            'status' => $this->faker->randomElement(["Pending","Terima","Tolak"]),
            'user_id' => User::factory(),
        ];
    }
}
