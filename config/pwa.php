<?php

return [
    'install-button' => false, // Show or hide the install button globally.

    'manifest' => [
        'name' => 'Faunafond',
        'short_name' => 'Faunafond',
        'background_color' => '#6777ef',
        'display' => 'fullscreen',
        'description' => 'A Progressive Web Application setup for Laravel projects.',
        'theme_color' => '#6777ef',
        'icons' => [
            [
                'src' => '/images/icons/logo-192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
            ],
            [
                'src' => '/images/icons/logo-512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
            ],
        ],
    ],

    'debug' => env('APP_DEBUG', false), // Show or hide console.log in the browser globally.


    'livewire-app' => false,
];
