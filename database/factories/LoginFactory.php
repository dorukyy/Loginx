<?php

namespace dorukyy\loginx\Database\Factories;

use dorukyy\loginx\Models\Login;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoginFactory extends Factory
{
    protected $model = Login::class;

    public function definition(): array
    {
        return [
            'ip' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'headers' => $this->faker->text,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'found_type' => $this->faker->randomElement(['email', 'username', 'phone']),
            'user_input' => $this->faker->email,
            'user_id' => $this->faker->numberBetween(1, 100),
        ];
    }
}
