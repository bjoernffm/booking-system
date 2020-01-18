<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Mobile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $number = $phoneUtil->parse($value);

            if ($phoneUtil->getNumberType($number) === \libphonenumber\PhoneNumberType::MOBILE) {
                return true;
            } else {
                return false;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            try {
                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                $number = $phoneUtil->parse($value, 'DE');

                if ($phoneUtil->getNumberType($number) === \libphonenumber\PhoneNumberType::MOBILE) {
                    return true;
                } else {
                    return false;
                }
            } catch (\libphonenumber\NumberParseException $e) {
                return false;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid mobile number.';
    }
}
