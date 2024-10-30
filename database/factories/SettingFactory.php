<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Setting;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'jam_masuk' => $this->faker->time(),
            'max_jam_masuk' => $this->faker->time(),
            'jam_keluar' => $this->faker->time(),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->randomFloat(6, 0, 999.999999),
            'radius' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
