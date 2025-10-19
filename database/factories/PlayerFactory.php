<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'first_name' => [
                'en' => $firstName,
                'ka' => $firstName, // You can customize Georgian translations if needed
            ],
            'last_name' => [
                'en' => $lastName,
                'ka' => $lastName, // You can customize Georgian translations if needed
            ],
            'country_id' => Country::inRandomOrder()->first()?->id ?? Country::factory(),
            'age' => rand(18, 40),
            'position' => fake()->randomElement(['goalkeeper', 'defender', 'midfielder', 'attacker']),
            'market_value' => 1000000,
            'is_on_transfer_list' => false,
            'asking_price' => null,
        ];
    }
}
