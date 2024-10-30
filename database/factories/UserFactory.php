<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nip' => $this->faker->regexify('[A-Za-z0-9]{6}'),
            'nama' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
            'tanggal_lahir' => $this->faker->date(),
            'photo' => $this->faker->text,
            'jabatan' => $this->faker->randomElement(["Manager","CEO","IT Support"]),
            'level' => $this->faker->randomElement(["admin","karyawan"]),
        ];
    }
}
