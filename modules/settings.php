<?php

return [
    'settings' => [

        // directories
        'modules_dir' => APPLICATION_PATH . '/modules/',
        'migrations_dir' => APPLICATION_PATH . '/db/migrations/',
        'tests_dir' => APPLICATION_PATH . '/tests/',

        'modules' => [
            'martynbiz-core' => 'MartynBiz\\Slim\\Module\\Core\\Module',
            'martynbiz-auth' => 'MartynBiz\\Slim\\Module\\Auth\\Module',
            'martynbiz-register' => 'MartynBiz\\Slim\\Module\\Register\\Module',
            'app' => 'App\\Module',
        ],

        'song_files' => [
            'path_pattern' => '/^.*\/(.*)\/(.*)\/(.*)\..*?$/',
            'api_key' => '73a4c2716ad6250f92d0210140e47a0c',
        ],
    ],
];
