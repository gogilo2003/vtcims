<?php
namespace App\Support;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

final class Util
{
    /**
     * Format the phone number to E.164 format.
     *
     * @param  string|null  $phoneNumber
     * @return string|null
     */
    static function formatPhoneNumber($phoneNumber)
    {

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            if ($phoneNumber) {
                $values = explode(",", $phoneNumber);

                if (count($values)) {
                    $phoneNumbers = [];
                    foreach ($values as $value) {
                        $phoneNumber = $phoneNumberUtil->parse(trim($value), 'KE'); // Assuming Kenyan numbers
                        $formattedNumber = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
                        // $formattedNumber now contains the phone number in Kenyan E164 format
                        $phoneNumbers[] = $formattedNumber;
                    }
                    return implode(',', $phoneNumbers);
                } else {
                    $phoneNumber = $phoneNumberUtil->parse($phoneNumber, 'KE'); // Assuming Kenyan numbers
                    $formattedNumber = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
                    // $formattedNumber now contains the phone number in Kenyan E164 format
                    return $formattedNumber;
                }
            }
        } catch (\libphonenumber\NumberParseException $e) {
            // Handle parsing errors
        }

        return;
    }

}
