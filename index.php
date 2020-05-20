<?php
define('ROOTPATH', __DIR__);
define('VIEW_PATH', __DIR__ . '/App/Resources/Views');
define('CONTROLLERS_PATH', __DIR__ . '/App/Http/Controllers');
define('CONFIG_PATH', __DIR__ . '/App/Config');
define('MODELS_PATH', __DIR__ . '/App/Models');

define('ENVIRONMENT_PROD', 'production');
define('ENVIRONMENT_DEV', 'development');

define('ENVIRONMENT', ENVIRONMENT_DEV);

require_once __DIR__ . '/App/ActiveRecord/ActiveRecord.php';
require __DIR__ . '/App/App.php';
require __DIR__ . '/App/Helpers/Paginator.php';
require __DIR__ . '/App/Helpers/Sorter.php';

session_start();
if (ENVIRONMENT === ENVIRONMENT_DEV) {
    error_reporting(E_ALL);
}

App::init();
App::$kernel->launch();
