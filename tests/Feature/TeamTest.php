<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\CountrySeeder;

uses()->group('teams');

test('user can view their team', function () {
    $this->seed(CountrySeeder::class);
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->getJson('/api/team', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'team' => ['id', 'name', 'country_id', 'budget'],
        ]);
});

test('user can update their team name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->putJson('/api/team', [
        'name' => 'New Team Name',
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);

    $team->refresh();
    expect($team->name)->toBe('New Team Name');
});

test('user can update their team country', function () {
    $this->seed(CountrySeeder::class);

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $newCountry = Country::where('id', '!=', $team->country_id)->first();

    $response = $this->putJson('/api/team', [
        'country_id' => $newCountry->id,
    ], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);

    $team->refresh();
    expect($team->country_id)->toBe($newCountry->id);
});

test('user without team gets 403', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->getJson('/api/team', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(403);
});
