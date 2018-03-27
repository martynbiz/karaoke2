<?php
$settings = [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,

        'debugbar' => [
            'enabled' => false,
            'base_url' => '/phpdebugbar',
        ],

        'eloquent' => [
            'driver' => 'mysql',
    		'host' => 'localhost',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],

        // Renderer settings
        'renderer' => [
            'folders' => [
                APPLICATION_PATH . '/templates',
            ],
            'ext' => 'phtml',
            'autoescape' => false,
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // i18n
        'i18n' => [

            // when the target locale is missing a translation/ template this the
            // fallback locale to use (probably "en")
            'default_locale' => 'en',

            // this is the type of the translation files using by zend-i18n
            'type' => 'phparray',

            // where the translation files are stored
            'file_path' => APPLICATION_PATH . '/languages/',
        ],

        'session' => [

            // namespace within the session storage
            'segment_name' => '__budget',
        ],

        'song_files' => [
            'path_pattern' => '/^.*\/(.*)\/(.*)\/(.*)\..*?$/',
            'api_key' => '73a4c2716ad6250f92d0210140e47a0c',
        ],
    ],
];

// local settings
$localSettingsPath = realpath(APPLICATION_PATH . '/settings/settings-' . APPLICATION_ENV . '.php');
if (file_exists($localSettingsPath)) {
    $settings = array_replace_recursive($settings, include $localSettingsPath);
}

return $settings;
