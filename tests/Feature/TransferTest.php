<?php

declare(strict_types=1);

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\CountrySeeder;

uses()->group('transfers');

test('user can list player for transfer', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $player = Player::factory()->create(['team_id' => $team->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->postJson("/api/players/{$player->id}/list-for-transfer", [
        'asking_price' => 1500000,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('players', [
        'id' => $player->id,
        'is_on_transfer_list' => true,
        'asking_price' => 1500000,
    ]);
});

test('user can buy player from transfer list', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->create(['user_id' => $seller->id, 'budget' => 5000000]);
    $player = Player::factory()->create([
        'team_id' => $sellerTeam->id,
        'is_on_transfer_list' => true,
        'asking_price' => 1500000,
        'market_value' => 1000000,
    ]);

    $buyer = User::factory()->create();
    $buyerTeam = Team::factory()->create(['user_id' => $buyer->id, 'budget' => 5000000]);
    $token = $buyer->createToken('test-token')->plainTextToken;

    $response = $this->postJson('/api/transfers/buy', [
        'player_id' => $player->id,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);

    // Check player was transferred
    $player->refresh();
    expect($player->team_id)->toBe($buyerTeam->id);
    expect($player->is_on_transfer_list)->toBeFalse();

    // Check market value increased (10-100%)
    expect($player->market_value)->toBeGreaterThanOrEqual(1100000);
    expect($player->market_value)->toBeLessThanOrEqual(2000000);

    // Check budgets updated
    $sellerTeam->refresh();
    $buyerTeam->refresh();
    expect((int) $sellerTeam->budget)->toBe(6500000);
    expect((int) $buyerTeam->budget)->toBe(3500000);
});

test('user cannot buy own player', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $player = Player::factory()->create([
        'team_id' => $team->id,
        'is_on_transfer_list' => true,
        'asking_price' => 1500000,
    ]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->postJson('/api/transfers/buy', [
        'player_id' => $player->id,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(400);
});

test('user cannot buy player with insufficient funds', function () {
    $seller = User::factory()->create();
    $sellerTeam = Team::factory()->create(['user_id' => $seller->id]);
    $player = Player::factory()->create([
        'team_id' => $sellerTeam->id,
        'is_on_transfer_list' => true,
        'asking_price' => 6000000,
    ]);

    $buyer = User::factory()->create();
    $buyerTeam = Team::factory()->create(['user_id' => $buyer->id, 'budget' => 5000000]);
    $token = $buyer->createToken('test-token')->plainTextToken;

    $response = $this->postJson('/api/transfers/buy', [
        'player_id' => $player->id,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(400);
});

test('user can view transfer list', function () {
    $this->seed(CountrySeeder::class);
    $user = User::factory()->create();
    Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $team = Team::factory()->create();
    Player::factory()->create([
        'team_id' => $team->id,
        'is_on_transfer_list' => true,
        'asking_price' => 1500000,
    ]);

    $response = $this->getJson('/api/transfer-list', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'players' => [
                '*' => ['id', 'first_name', 'last_name', 'asking_price'],
            ],
        ]);
});
