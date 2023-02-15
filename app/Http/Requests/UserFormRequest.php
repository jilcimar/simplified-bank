<?php

namespace App\Http\Requests;

use App\Enum\UserType;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UserFormRequest extends CrudRequest
{
    /**
     * Type of class being validated.
     *
     * @var string
     */
    protected $type = User::class;


    /**
     * Rules when creating resource.
     *
     * @return array
     */
    protected function createRules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }

    /**
     * Base rules for both creating and editing the resource.
     *
     * @return array
     */
    protected function baseRules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'type' => ['required', new Enum(UserType::class)]
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'Tipo'
        ];
    }
}
