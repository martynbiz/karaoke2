<?php
/**
 * This include file allows this code to be shared with the website and cli tool
 */

// TODO move these
// Environments
define('ENV_PRODUCTION', 'production');
define('ENV_TESTING', 'testing');
define('ENV_DEVELOPMENT', 'development');

// HTTP STATUS CODES
define('HTTP_CONTINUE', 100);
define('HTTP_SWITCHING_PROTOCOLS', 101);

// [Successful 2xx]
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_ACCEPTED', 202);
define('HTTP_NONAUTHORITATIVE_INFORMATION', 203);
define('HTTP_NO_CONTENT', 204);
define('HTTP_RESET_CONTENT', 205);
define('HTTP_PARTIAL_CONTENT', 206);

// [Redirection 3xx]
define('HTTP_MULTIPLE_CHOICES', 300);
define('HTTP_MOVED_PERMANENTLY', 301);
define('HTTP_FOUND', 302);
define('HTTP_SEE_OTHER', 303);
define('HTTP_NOT_MODIFIED', 304);
define('HTTP_USE_PROXY', 305);
define('HTTP_UNUSED', 306);
define('HTTP_TEMPORARY_REDIRECT', 307);

// [Client Error 4xx]
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED ', 401);
define('HTTP_PAYMENT_REQUIRED', 402);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_METHOD_NOT_ALLOWED', 405);
define('HTTP_NOT_ACCEPTABLE', 406);
define('HTTP_PROXY_AUTHENTICATION_REQUIRED', 407);
define('HTTP_REQUEST_TIMEOUT', 408);
define('HTTP_CONFLICT', 409);
define('HTTP_GONE', 410);
define('HTTP_LENGTH_REQUIRED', 411);
define('HTTP_PRECONDITION_FAILED', 412);
define('HTTP_REQUEST_ENTITY_TOO_LARGE', 413);
define('HTTP_REQUEST_URI_TOO_LONG', 414);
define('HTTP_UNSUPPORTED_MEDIA_TYPE', 415);
define('HTTP_REQUESTED_RANGE_NOT_SATISFIABLE', 416);
define('HTTP_EXPECTATION_FAILED', 417);

// [Server Error 5xx]
define('HTTP_INTERNAL_SERVER_ERROR', 500);
define('HTTP_NOT_IMPLEMENTED', 501);
define('HTTP_BAD_GATEWAY', 502);
define('HTTP_SERVICE_UNAVAILABLE', 503);
define('HTTP_GATEWAY_TIMEOUT', 504);
define('HTTP_VERSION_NOT_SUPPORTED', 505);





// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

$classLoader = require __DIR__ . '/../vendor/autoload.php';

// App settings
$appSettings = require APPLICATION_PATH . '/modules/settings.php';

// Module settings (autoload)
$moduleSettings = [];
foreach (array_keys($appSettings['settings']['modules']) as $dir) {
    if ($path = realpath($appSettings['settings']['modules_dir'] . $dir . '/settings.php')) {
        $moduleSettings = array_merge_recursive($moduleSettings, require $path);
    }
}

// Environment settings
$envSettings = [];
if ($path = realpath(APPLICATION_PATH . '/modules/settings-' . APPLICATION_ENV . '.php')) {
    $envSettings = require $path;
}

// Instantiate the app
$settings = array_merge_recursive($moduleSettings, $appSettings, $envSettings);
$app = new Slim\App($settings);
