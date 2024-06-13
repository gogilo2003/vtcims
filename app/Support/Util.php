<?php
namespace App\Support;

use Illuminate\Support\Str;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Support\Facades\Route;
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

    static function getRoutes()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            // Extract route name and generate corresponding caption
            $routeName = $route->getName();
            $routeCaption = ucwords(str_replace('-', ' ', $routeName));

            // Return route name and caption as an associative array
            return (object) [
                'name' => $routeName,
                'caption' => $routeCaption
            ];
        })->filter(function ($route) {
            if ($route->name) {
                if (
                    Str::startsWith($route->name, 'ignition')
                    || Str::startsWith($route->name, 'sanctum')
                    || Str::startsWith($route->name, 'verification')
                    || Str::startsWith($route->name, 'password')
                    || Str::startsWith($route->name, 'profile')
                    || Str::startsWith($route->name, 'login')
                    || Str::startsWith($route->name, 'logout')
                ) {
                    return false;
                }
                return true;
            }
            return false;
        });

        return collect($routes->sortBy('name')->values()->all());
    }

    static function getImageBase64($path)
    {
        $exists = file_exists($path);

        // Ensure the file exists
        if ($exists) {
            $file = file_get_contents($path);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($file);
            return $base64;
        }

        return null;
    }
}
