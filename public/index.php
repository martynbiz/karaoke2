<?php

require_once 'bootstrap.php';

session_start();

// initialize all modules in settings > modules > autoload [...]
$moduleInitializer = new MartynBiz\Slim\Module\Initializer($settings['settings']['modules']);
$moduleInitializer->initModules($app);
$app->run();
