<?php

namespace App\Http\Requests;

use App\Enum\UserType;
use App\Models\Transaction;
use App\Rules\TransactionAuthorizationRule;
use App\Rules\TypeUserPersonRule;
use App\Rules\WalletBalanceRule;
use Illuminate\Validation\Rule;

class TransactionFormRequest extends CrudRequest
{
    /**
     * Type of class being validated.
     *
     * @var string
     */
    protected $type = Transaction::class;

    /**
     * Base rules for both creating and editing the resource.
     *
     * @return array
     */
    protected function baseRules(): array
    {
        return [
            'value' => ['required', 'numeric', new WalletBalanceRule(),
                new TransactionAuthorizationRule()],
            'payer' => ['required', 'exists:users,id', 'different:payee', new TypeUserPersonRule()],
            'payee' => ['required', 'exists:users,id']
        ];
    }
}
