<?php

return [
    'install-button' => true, // Show or hide the install button globally.

    'manifest' => [
        'name' => 'Faunafond',
        'short_name' => 'LPT',
        'background_color' => '#6777ef',
        'display' => 'fullscreen',
        'description' => 'A Progressive Web Application setup for Laravel projects.',
        'theme_color' => '#6777ef',
        'icons' => [
            [
                'src' => 'Logo.png',
                'sizes' => '512x512',
                'type' => 'image/jpg',
            ],
        ],
    ],

    'debug' => env('APP_DEBUG', false), // Show or hide console.log in the browser globally.
    
    
    'livewire-app' => false,
];
