<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'username' => $this->faker->userName(),
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // default password
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'photo' => $this->faker->imageUrl(200, 200, 'people'),
                'phone' => $this->faker->phoneNumber(),
                'address' => $this->faker->address(),
                'role' => $this->faker->randomElement(['admin', 'vendor', 'user']),
                'status' => $this->faker->randomElement(['active', 'inactive']),
                'lastseen' => now()->subMinutes(rand(1, 5000)),
                'vendor_join_date' => $this->faker->year(),
                'shop_name' => $this->faker->optional()->company(),
                'vendor_info' => $this->faker->optional()->text(),
                'user_slug' => Str::slug($this->faker->userName())
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
