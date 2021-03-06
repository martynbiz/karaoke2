<?php

return [
    'settings' => [

        // directories
        'folders' => [
            'modules' => APPLICATION_PATH . '/app/modules/',
            'db' => APPLICATION_PATH . '/db/',
            'tests' => APPLICATION_PATH . '/tests/',
        ],

        'modules' => [
            'martynbiz-core' => 'MartynBiz\\Slim\\Module\\Core\\Module',
            'martynbiz-auth' => 'MartynBiz\\Slim\\Module\\Auth\\Module',
            'martynbiz-register' => 'MartynBiz\\Slim\\Module\\Register\\Module',
            'karaoke' => 'MartynBiz\\Slim\\Module\\Karaoke\\Module',
        ],

        'song_files' => [
            'path_pattern' => '/^.*\/(.*)\/(.*)\/(.*)\..*?$/',
            'api_key' => '73a4c2716ad6250f92d0210140e47a0c',
        ],
    ],
];
