<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = [
            ['name' => ['en' => 'England', 'ka' => 'ინგლისი'], 'code' => 'ENG'],
            ['name' => ['en' => 'Spain', 'ka' => 'ესპანეთი'], 'code' => 'ESP'],
            ['name' => ['en' => 'Germany', 'ka' => 'გერმანია'], 'code' => 'DEU'],
            ['name' => ['en' => 'Italy', 'ka' => 'იტალია'], 'code' => 'ITA'],
            ['name' => ['en' => 'France', 'ka' => 'საფრანგეთი'], 'code' => 'FRA'],
            ['name' => ['en' => 'Brazil', 'ka' => 'ბრაზილია'], 'code' => 'BRA'],
            ['name' => ['en' => 'Argentina', 'ka' => 'არგენტინა'], 'code' => 'ARG'],
            ['name' => ['en' => 'Portugal', 'ka' => 'პორტუგალია'], 'code' => 'PRT'],
            ['name' => ['en' => 'Netherlands', 'ka' => 'ნიდერლანდები'], 'code' => 'NLD'],
            ['name' => ['en' => 'Belgium', 'ka' => 'ბელგია'], 'code' => 'BEL'],
        ];

        $country = fake()->randomElement($countries);

        return [
            'name' => $country['name'],
            'code' => $country['code'],
        ];
    }
}
