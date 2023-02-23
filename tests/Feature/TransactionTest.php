<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can transfer value', function () {
    $user = createUser();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' =>  'password'
    ]);

    $authToken = $response->json();

    $company = createUser(\App\Enum\UserType::Company->value);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken['token']
    ])->postJson('/api/transaction', [
        'value' => 100,
        'payer' =>  $user->id,
        'payee' =>  $company->id,
    ]);

    $response->assertStatus(201);
})->group('transactions');

test('company cannot transfer value', function () {
    $user = createUser();
    $company = createUser(\App\Enum\UserType::Company->value);

    $response = $this->postJson('/api/login', [
        'email' => $company->email,
        'password' =>  'password'
    ]);

    $authToken = $response->json();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken['token']
    ])->postJson('/api/transaction', [
        'value' => 100,
        'payer' =>  $company->id,
        'payee' =>  $user->id,
    ]);

    $response->assertStatus(422);
})->group('transactions');

test('user cannot transfer value greater than the balance', function () {
    $user = createUser();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' =>  'password'
    ]);

    $authToken = $response->json();

    $company = createUser(\App\Enum\UserType::Company->value);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken['token']
    ])->postJson('/api/transaction', [
        'value' => $user->wallet->amount + 1,
        'payer' =>  $user->id,
        'payee' =>  $company->id,
    ]);

    $response->assertStatus(422);
})->group('transactions');

test('user cannot transfer value to himself', function () {
    $user = createUser();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' =>  'password'
    ]);

    $authToken = $response->json();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken['token']
    ])->postJson('/api/transaction', [
        'value' => 100,
        'payer' =>  $user->id,
        'payee' =>  $user->id,
    ]);

    $response->assertStatus(422);
})->group('transactions');

test('user needs to be logged in to transfer value', function () {
    $user = createUser();
    $company = createUser(\App\Enum\UserType::Company->value);

    $response = $this->postJson('/api/transaction', [
        'value' => 100,
        'payer' =>  $user->id,
        'payee' =>  $company->id,
    ]);

    $response->assertStatus(400);
})->group('transactions');

test('payer needs to be logged in to transfer', function () {
    $user = createUser();
    $unauthenticatedUser = createUser();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' =>  'password'
    ]);

    $authToken = $response->json();

    $company = createUser(\App\Enum\UserType::Company->value);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $authToken['token']
    ])->postJson('/api/transaction', [
        'value' => 100,
        'payer' =>  $unauthenticatedUser->id,
        'payee' =>  $company->id,
    ]);

    $response->assertStatus(422);
})->group('transactions');
