<?php

namespace App\Rules;

use App\Enum\UserType;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Validation\InvokableRule;

class TypeUserPersonRule implements InvokableRule
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
        if ($payer->type != UserType::Person) {
            $fail(trans('errors.permission_denied'));
        }
    }
}
