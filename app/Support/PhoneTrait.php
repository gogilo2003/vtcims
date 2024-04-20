<?php

declare(strict_types=1);

namespace App\Support;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

trait PhoneTrait
{
    public function prepareForValidation(): void
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            if ($this->phone) {
                $values = explode(",", $this->phone);

                if (count($values)) {
                    $phoneNumbers = [];
                    foreach ($values as $value) {
                        $phoneNumber = $phoneNumberUtil->parse(trim($value), 'KE'); // Assuming Kenyan numbers
                        $formattedNumber = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
                        // $formattedNumber now contains the phone number in Kenyan E164 format
                        $phoneNumbers[] = $formattedNumber;
                    }
                    $this->merge([
                        'phone' => implode(',', $phoneNumbers),
                    ]);
                } else {
                    $phoneNumber = $phoneNumberUtil->parse($this->phone, 'KE'); // Assuming Kenyan numbers
                    $formattedNumber = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
                    // $formattedNumber now contains the phone number in Kenyan E164 format
                    $this->merge([
                        'phone' => $formattedNumber,
                    ]);
                }
            }
        } catch (\libphonenumber\NumberParseException $e) {
            // Handle parsing errors
        }

    }
}
