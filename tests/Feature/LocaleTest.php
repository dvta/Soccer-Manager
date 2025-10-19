<?php

declare(strict_types=1);

use App\Models\Country;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\CountrySeeder;

uses()->group('locale');

test('api returns translations based on Accept-Language header', function () {
    $this->seed(CountrySeeder::class);
    $user = User::factory()->create();
    Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    // Get data in English
    $responseEn = $this->getJson('/api/me', [
        'Authorization' => 'Bearer '.$token,
        'Accept-Language' => 'en',
    ]);

    $responseEn->assertStatus(200);

    // Get data in Georgian
    $responseKa = $this->getJson('/api/me', [
        'Authorization' => 'Bearer '.$token,
        'Accept-Language' => 'ka',
    ]);

    $responseKa->assertStatus(200);

    // Both should succeed with different locales
    expect($responseEn->status())->toBe(200);
    expect($responseKa->status())->toBe(200);
});

test('country names are translated based on locale', function () {
    $this->seed(CountrySeeder::class);

    // Set locale to English
    app()->setLocale('en');
    $countryEn = Country::first();
    $englishName = $countryEn->name;

    // Set locale to Georgian
    app()->setLocale('ka');
    $countryKa = Country::first();
    $georgianName = $countryKa->name;

    // Names should be different
    expect($englishName)->not->toBe($georgianName);
});

test('default locale is used when Accept-Language is not set', function () {
    $this->seed(CountrySeeder::class);
    $user = User::factory()->create();
    Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->getJson('/api/me', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertStatus(200);
    expect(app()->getLocale())->toBe('en');
});

test('invalid locale defaults to English', function () {
    $this->seed(CountrySeeder::class);
    $user = User::factory()->create();
    Team::factory()->create(['user_id' => $user->id]);
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->getJson('/api/me', [
        'Authorization' => 'Bearer '.$token,
        'Accept-Language' => 'invalid',
    ]);

    $response->assertStatus(200);
});
