<?php

return array(

    'views_folder' => 'notifications',

    'email' => array(
        'enabled' => true,

        'from_email' => null,

        'cc_email' => null,

        'getter_email' => function($user) {
            return $user->email;
        },

        'getter_name' => function($user) {
            return $user->first_name . ' ' . $user->last_name;
        },
    ),

    'sms' => array(
        'enabled' => false,

        'getter_phone' => function($user) {
            return $user->phone;
        },

        // test credentials for Twilio
        'twilio' => array(
            'sid' => 'ACa4e541fc7a4b9ce6d8b4ce6e5ce4bf76',
            'token' => 'f9721795b08fc35a3cde31b3650141ef',
            'phone_number' => '+15005550006'
        )
    )
);