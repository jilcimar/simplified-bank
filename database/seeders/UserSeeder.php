<?php

namespace Database\Seeders;

use App\Enum\UserType;
use App\Models\User;
use App\Models\Wallet;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $walletPayer = Wallet::create([
            'amount' => 1000,
        ]);

        $walletPayee = Wallet::create([
            'amount' => 1000,
        ]);

        $faker = Factory::create('pt_BR');

        User::create(
            [
                'name' => 'User Payer 1',
                'email' => 'payer@email.com',
                'password' => bcrypt('password'),
                'type' => UserType::Person->value,
                'wallet_id' => $walletPayer->id,
                'cpf' => str_replace(['.' , '-'], '', $faker->cpf)
            ]
        );

        User::create(
            [
                'name' => 'User Payee 1',
                'email' => 'payee@email.com',
                'password' => bcrypt('password'),
                'type' => UserType::Company->value,
                'wallet_id' => $walletPayee->id,
                'cnpj' => str_replace(['.', '/' , '-'], '', $faker->cnpj)
            ]
        );
    }
}
