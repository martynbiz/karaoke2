<?php

require_once 'prepare.php';

session_start();

// initialize all modules in settings > modules > autoload [...]
$moduleInitializer = new MartynBiz\Slim\Module\Initializer($settings['settings']['module_initializer']);
$moduleInitializer->initModules($app);
$app->run();
