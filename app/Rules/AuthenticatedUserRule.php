<?php

namespace App\Rules;

use App\Enum\UserType;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Validation\InvokableRule;

class AuthenticatedUserRule implements InvokableRule
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
        if (auth()->user()->id != $value) {
            $fail(trans('errors.permission_denied_unauthenticated'));
        }
    }
}
