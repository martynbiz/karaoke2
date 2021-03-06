<?php

// ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');

// Environments
define('ENV_PRODUCTION', 'production');
define('ENV_TESTING', 'testing');
define('ENV_DEVELOPMENT', 'development');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'vagranttest'));

require APPLICATION_PATH . '/vendor/autoload.php';

if (! isset($_SESSION)) $_SESSION = [];
