<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class WithdrawBalance implements Rule
{

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= user()->balance;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The amount exceeds your balance, please try again';
    }
}
