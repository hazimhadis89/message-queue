<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Iso8601 implements InvokableRule
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
        if ($value && valid_iso8601($value) === false) {
            $fail('The :attribute must be a valid ISO8601 string.');
        }
    }
}
