<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\UpdateTeamDTO;
use App\Enums\PlayerPosition;
use App\Models\Country;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;

class TeamService
{
    public function createTeamForUser(User $user): Team
    {
        $teamName = $user->name."'s Team";

        $team = Team::create([
            'user_id' => $user->id,
            'name' => [
                'en' => $teamName,
                'ka' => $user->name.'-ის გუნდი',
            ],
            'country_id' => $this->getRandomCountryId(),
            'budget' => 5000000,
        ]);

        $this->createPlayersForTeam($team);

        return $team;
    }

    public function updateTeam(Team $team, UpdateTeamDTO $dto): Team
    {
        $team->update($dto->toArray());

        return $team->fresh();
    }

    public function getTeam(Team $team): Team
    {
        return $team->load('players.country', 'country');
    }

    private function createPlayersForTeam(Team $team): void
    {
        $positions = [
            PlayerPosition::GOALKEEPER->value => 3,
            PlayerPosition::DEFENDER->value => 6,
            PlayerPosition::MIDFIELDER->value => 6,
            PlayerPosition::ATTACKER->value => 5,
        ];

        foreach ($positions as $position => $count) {
            for ($i = 0; $i < $count; $i++) {
                $firstName = fake()->firstName();
                $lastName = fake()->lastName();

                Player::create([
                    'team_id' => $team->id,
                    'first_name' => [
                        'en' => $firstName,
                        'ka' => $firstName,
                    ],
                    'last_name' => [
                        'en' => $lastName,
                        'ka' => $lastName,
                    ],
                    'country_id' => $this->getRandomCountryId(),
                    'age' => rand(18, 40),
                    'position' => $position,
                    'market_value' => 1000000,
                    'is_on_transfer_list' => false,
                ]);
            }
        }
    }

    private function getRandomCountryId(): int
    {
        $country = Country::inRandomOrder()->first();

        if ($country === null) {
            throw new \RuntimeException('No countries found in database. Please run CountrySeeder first.');
        }

        return (int) $country->id;
    }
}
