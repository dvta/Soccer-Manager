<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\CountrySeeder;

uses()->group('players');

test('user can view transfer list', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    Player::factory()->count(3)->create([
        'team_id' => $team->id,
        'is_on_transfer_list' => true,
        'asking_price' => 1000000,
    ]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->getJson('/api/transfer-list', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'players' => [
                '*' => ['id', 'first_name', 'last_name', 'position', 'age', 'market_value'],
            ],
        ]);
});

test('user can update player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $player = Player::factory()->create(['team_id' => $team->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->putJson("/api/players/{$player->id}", [
        'first_name' => 'Updated First',
        'last_name' => 'Updated Last',
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);

    $player->refresh();
    expect($player->first_name)->toBe('Updated First');
    expect($player->last_name)->toBe('Updated Last');
});

test('user can update player country', function () {
    $this->seed(CountrySeeder::class);

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $player = Player::factory()->create(['team_id' => $team->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $newCountry = Country::where('id', '!=', $player->country_id)->first();

    $response = $this->putJson("/api/players/{$player->id}", [
        'country_id' => $newCountry->id,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);

    $player->refresh();
    expect($player->country_id)->toBe($newCountry->id);
});

test('user cannot update another users player', function () {
    $user1 = User::factory()->create();
    $team1 = Team::factory()->create(['user_id' => $user1->id]);
    $player1 = Player::factory()->create(['team_id' => $team1->id]);

    $user2 = User::factory()->create();
    $token2 = $user2->createToken('test-token')->plainTextToken;

    $response = $this->putJson("/api/players/{$player1->id}", [
        'first_name' => 'Hacked',
        'last_name' => 'Name',
    ], [
        'Authorization' => 'Bearer '.$token2,
    ]);

    $response->assertStatus(403);
});
