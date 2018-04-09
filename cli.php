<?php

require_once 'public/bootstrap.php';

$modules = $settings['settings']['modules'];

$command = $argv[1];
$moduleName = @$argv[2]; // optional

switch ($command) {
    case 'modules:install':
        if ($moduleName) {
            if (isset($modules[$moduleName])) {
                $moduleClassName = $modules[$moduleName];
                $module = new $moduleClassName();
                if (method_exists($module, 'copyFiles')) {
                    $module->copyFiles($settings['settings']['folders']);
                }
            } else {
                echo "$moduleName module not found\n";
            }
        } else {
            foreach($modules as $name => $moduleClassName) {
                $module = new $moduleClassName();
                if (method_exists($module, 'copyFiles')) {
                    $module->copyFiles($settings['settings']['folders']);
                }
            }
        }
        break;
    case 'modules:remove':
        if ($moduleName) {
            if (isset($modules[$moduleName])) {
                $moduleClassName = $modules[$moduleName];
                $module = new $moduleClassName();
                if (method_exists($module, 'removeFiles')) {
                    $module->removeFiles($settings['settings']['folders']);
                }
            } else {
                echo "$moduleName module not found\n";
            }
        } else {
            foreach($modules as $name => $moduleClassName) {
                $module = new $moduleClassName();
                if (method_exists($module, 'removeFiles')) {
                    $module->removeFiles($settings['settings']['folders']);
                }
            }
        }
        break;
}
