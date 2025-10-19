<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => ['en' => 'England', 'ka' => 'ინგლისი'],
                'code' => 'ENG',
            ],
            [
                'name' => ['en' => 'Spain', 'ka' => 'ესპანეთი'],
                'code' => 'ESP',
            ],
            [
                'name' => ['en' => 'Germany', 'ka' => 'გერმანია'],
                'code' => 'DEU',
            ],
            [
                'name' => ['en' => 'Italy', 'ka' => 'იტალია'],
                'code' => 'ITA',
            ],
            [
                'name' => ['en' => 'France', 'ka' => 'საფრანგეთი'],
                'code' => 'FRA',
            ],
            [
                'name' => ['en' => 'Brazil', 'ka' => 'ბრაზილია'],
                'code' => 'BRA',
            ],
            [
                'name' => ['en' => 'Argentina', 'ka' => 'არგენტინა'],
                'code' => 'ARG',
            ],
            [
                'name' => ['en' => 'Portugal', 'ka' => 'პორტუგალია'],
                'code' => 'PRT',
            ],
            [
                'name' => ['en' => 'Netherlands', 'ka' => 'ნიდერლანდები'],
                'code' => 'NLD',
            ],
            [
                'name' => ['en' => 'Belgium', 'ka' => 'ბელგია'],
                'code' => 'BEL',
            ],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(['code' => $country['code']], $country);
        }
    }
}
