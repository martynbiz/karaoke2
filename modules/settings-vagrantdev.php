<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        'debugbar' => [
            'enabled' => false,
        ],

        'eloquent' => [
            'database'  => 'karaoke2_dev',
            'username'  => 'root',
            'password'  => 'vagrant1',
        ],

        'song_files' => [
            'parent_dir' => '/var/www/karaoke/public/media',
        ],
    ],
];
