<?php return array(
    'name' => env('APP_NAME', 'Name of School'),
    'adm_number_pattern' => env('VTCIMS_ADM_NUMBER_PATTERN', '{course}/{id}/{year}'),
    'logo' => [
        'logo1' => env('VTCIMS_LOGO_1', '/logo.png'),
        'logo2' => env('VTCIMS_LOGO_2', '/logo.png'),
    ],
    'phone' => env('VTCIMS_PHONE'),
    'email' => env('VTCIMS_EMAIL'),
    'box_no' => env('VTCIMS_BOX_NO'),
    'post_code' => env('VTCIMS_POST_CODE'),
    'town' => env('VTCIMS_TOWN'),
    'website' => env('VTCIMS_WEBSITE'),
    'facebook' => env('VTCIMS_FACEBOOK'),
    'twitter' => env('VTCIMS_TWITTER'),
    'youtube' => env('VTCIMS_YOUTUBE'),
    'linkedin' => env('VTCIMS_LINKEDIN'),
    'physical_address' => env('VTCIMS_PHYSICAL_ADDRESS'),
);
