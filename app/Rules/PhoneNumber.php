<?php

namespace App\Rules;

use Closure;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            if ($value) {
                $values = explode(",", $value);

                if (count($values)) {
                    foreach ($values as $item) {
                        $phoneNumber = $phoneNumberUtil->parse($item, 'KE');
                        if (!$phoneNumberUtil->isValidNumber($phoneNumber)) {
                            $fail('The :attribute is not a valid phone number.');
                        }
                    }
                } else {
                    $phoneNumber = $phoneNumberUtil->parse($value, 'KE');
                    if (!$phoneNumberUtil->isValidNumber($phoneNumber)) {
                        $fail('The :attribute is not a valid phone number.');
                    }
                }
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $fail('The :attribute is not a valid phone number.');
        }
    }
}
