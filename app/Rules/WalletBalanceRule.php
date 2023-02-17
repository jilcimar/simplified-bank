<?php

namespace App\Rules;

use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Validation\InvokableRule;

class WalletBalanceRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $payer = (new UserRepository())->find(request('payer'));
        if ($payer->wallet->amount <= $value || $payer->wallet->amount === 0) {
            $fail(trans('errors.insufficient_funds'));
        }
    }
}
