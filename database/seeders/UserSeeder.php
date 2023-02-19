<?php

namespace Database\Seeders;

use App\Enum\UserType;
use App\Models\User;
use App\Models\Wallet;
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

        User::create(
            [
                'name' => 'User Payer 1',
                'email' => 'payer@email.com',
                'password' => bcrypt('password'),
                'type' => UserType::Person->value,
                'wallet_id' => $walletPayer->id
            ]
        );

        User::create(
            [
                'name' => 'User Payee 1',
                'email' => 'payee@email.com',
                'password' => bcrypt('password'),
                'type' => UserType::Company->value,
                'wallet_id' => $walletPayee->id
            ]
        );
    }
}
