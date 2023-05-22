<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Jayrods\ProductInventory\Helper\Environment as Env;
use Jayrods\ProductInventory\Http\Middleware\MiddlewareQueue;

define('ROOT_DIR', dirname(__DIR__));
define('SLASH', DIRECTORY_SEPARATOR);

define('BIN_DIR', ROOT_DIR . SLASH . 'bin' . SLASH);
define('CONFIG_DIR', ROOT_DIR . SLASH . 'config' . SLASH);
define('FUNCTIONS_DIR', ROOT_DIR . SLASH . 'functions' . SLASH);
define('PUBLIC_DIR', ROOT_DIR . SLASH . 'public' . SLASH);
define('RESOURCES_DIR', ROOT_DIR . SLASH . 'resources' . SLASH);
define('SRC_DIR', ROOT_DIR . SLASH . 'src' . SLASH);
define('STORAGE_DIR', ROOT_DIR . SLASH . 'storage' . SLASH);

define('CSS_DIR', RESOURCES_DIR . 'css' . SLASH);
define('IMG_DIR', RESOURCES_DIR . 'img' . SLASH);
define('VIEW_DIR', RESOURCES_DIR . 'view' . SLASH);

define('TEMPLATE_DIR', VIEW_DIR . 'template' . SLASH);
define('COMPONENTS_DIR', VIEW_DIR . 'components' . SLASH);

define('CACHE_DIR', STORAGE_DIR . 'cache' . SLASH);

define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

// Global flash message constants
define('FLASH_MESSAGE', 'flash_message');

// Environment variables loading
$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

// .env global constants definition
define('APP_URL', Env::env('APP_URL', 'http://localhost:8000'));
define('ENVIRONMENT', Env::env('ENVIRONMENT', 'production'));
define('CACHE_EXPIRATION_TIME', Env::env('CACHE_EXPIRATION_TIME', 30));

// Middlewares mapping and settings
MiddlewareQueue::setMap(
    include CONFIG_DIR . 'middlewares.php'
);

MiddlewareQueue::setDefault(array(
    'maintenance',
    'session'
));
