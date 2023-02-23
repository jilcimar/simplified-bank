<?php

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a new user', function () {
    $faker = Factory::create('pt_BR');
    $password = $faker->password . $faker->password;

    $data = [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $password,
        'password_confirmation' => $password,
        'type' => \App\Enum\UserType::Person->value,
        'cpf' => str_replace(['.' , '-'], '', $faker->cpf)
    ];

    $response = $this->post('api/users', $data);

    $response->assertStatus(201);

    $this->assertDatabaseHas('users', [
        'email' => $data['email'],
    ]);
})->group('integration');

it('validate required fields in user registration', function () {
    $faker = Factory::create('pt_BR');
    $password = $faker->password . $faker->password;

    $data = [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $password,
    ];

    $response = $this->post('api/users', $data);

    $response->assertStatus(422);
})->group('integration');
