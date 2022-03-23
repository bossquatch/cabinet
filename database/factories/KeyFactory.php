<?php

namespace Database\Factories;

use App\Models\Key;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class KeyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Key::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'owner_id' => User::all()->random()->id,
            'description' => $this->faker->sentence(),
            'value' => Str::random(10),
            'public' => false,
        ];
    }
}
