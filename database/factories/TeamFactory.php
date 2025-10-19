<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teamName = fake()->company().' FC';

        return [
            'user_id' => User::factory(),
            'name' => [
                'en' => $teamName,
                'ka' => $teamName, // You can customize Georgian translations if needed
            ],
            'country_id' => Country::inRandomOrder()->first()?->id ?? Country::factory(),
            'budget' => 5000000,
        ];
    }
}
