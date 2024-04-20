<?php
namespace App\Support;

use Illuminate\Support\Carbon;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

trait StudentPrepareTrait
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

        $this->merge(['date_of_admission' => Carbon::parse($this->date_of_admission)]);
        $this->merge(['date_of_birth' => Carbon::parse($this->date_of_birth)]);
    }
}
