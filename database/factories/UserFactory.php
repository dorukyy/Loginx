<?php

namespace dorukyy\loginx\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->userName,
            'phone' => $this->faker->phoneNumber,
            'referrer' => null,
            'country_id' => null,
            'address' => $this->faker->address,
            'avatar' => $this->faker->imageUrl,
            'blocked_at' => null,
            'blocked_until' => null,
            'blocked_reason' => null,
            'blocked_by_id' => null,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
