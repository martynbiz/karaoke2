<?php

return [
    'settings' => [
        'modules_dir' => APPLICATION_PATH . '/modules/',
        'module_initializer' => [
            'modules' => [
                'app' => 'App\\Module',
                'martynbiz-core' => 'MartynBiz\\Slim\\Module\\Core\\Module',
                'martynbiz-auth' => 'MartynBiz\\Slim\\Module\\Auth\\Module',
                'martynbiz-register' => 'MartynBiz\\Slim\\Module\\Register\\Module',
            ],
        ],
    ],
];
