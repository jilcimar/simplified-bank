<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can login', function () {
    $user = createUser();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' =>  'password'
    ]);

    $response->assertOk();
})->group('users');

test('user on session is valid between requests', function () {
    $user = createUser();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' =>  'password'
    ]);

    $authToken = $response->json();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken['token']
    ])->getJson('/api/users');

    $response->assertOk();
})->group('users');
