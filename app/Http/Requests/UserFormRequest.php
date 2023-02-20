<?php

namespace App\Http\Requests;

use App\Enum\UserType;
use App\Models\User;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use LaravelLegends\PtBrValidator\Rules\Cnpj;
use LaravelLegends\PtBrValidator\Rules\Cpf;

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
            'cpf' => [
                'required_if:type,person',
                'unique:users',
                'string',
                new Cpf()
            ],
            'cnpj' => [
                'required_if:type,company',
                'unique:users',
                'string',
                new Cnpj()
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
            'email' => ['required', 'email', 'unique:users'],
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
