<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Database\Seeders\CountrySeeder;

uses()->group('auth');

test('user can register', function () {
    $this->seed(CountrySeeder::class);

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'token',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);

    // Check that team was created with 20 players
    $user = User::where('email', 'test@example.com')->first();
    expect($user->team)->not->toBeNull();
    expect($user->team->players()->count())->toBe(20);
});

test('user can login', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'token',
        ]);
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
});

test('user can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->postJson('/api/logout', [], [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);
    expect($user->tokens()->count())->toBe(0);
});

test('authenticated user can get profile', function () {
    $this->seed(CountrySeeder::class);

    $user = User::factory()->create();
    Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->getJson('/api/me', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'user' => ['id', 'name', 'email'],
        ]);
});
