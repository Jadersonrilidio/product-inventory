<?php

declare(strict_types=1);

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\Router;
use Jayrods\ProductInventory\Http\Middleware\MiddlewareQueue;
use Psr\Container\ContainerInterface;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

/** @var ContainerInterface */
$diContainer = require CONFIG_DIR . 'dependencies.php';

$routes = require CONFIG_DIR . 'routes.php';

$router = new Router(
    new Request(),
    new MiddlewareQueue(),
    $diContainer,
    $routes
);

$router->handleRequest()->sendResponse();
