<?php

return [
    'settings' => [
        'modules_dir' => APPLICATION_PATH . '/modules/',
        'module_initializer' => [
            'modules' => [
                'martynbiz-core' => 'MartynBiz\\Slim\\Module\\Core\\Module',
                'martynbiz-auth' => 'MartynBiz\\Slim\\Module\\Auth\\Module',
                'martynbiz-register' => 'MartynBiz\\Slim\\Module\\Register\\Module',
                'app' => 'App\\Module',
            ],
        ],

        'song_files' => [
            'path_pattern' => '/^.*\/(.*)\/(.*)\/(.*)\..*?$/',
            'api_key' => '73a4c2716ad6250f92d0210140e47a0c',
        ],
    ],
];
