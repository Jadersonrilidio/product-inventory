<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Middleware;

use Closure;
use Jayrods\ProductInventory\Helper\Environment as Env;
use Jayrods\ProductInventory\Http\Controller\MaintenanceController;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\View;
use Jayrods\ProductInventory\Http\Middleware\Middleware;
use Jayrods\ProductInventory\Infrastructure\FlashMessage;

class MaintenanceMiddleware implements Middleware
{
    /**
     * Handle middleware execution.
     * 
     * @param Request $request
     * @param Closure $next The next middleware to be executed in queue.
     * 
     * @return bool
     */
    public function handle(Request $request, Closure $next): bool
    {
        $maintenance = Env::env('MAINTENANCE', 'false');

        if ($maintenance === 'true') {
            $this->callMaintenanceController($request);
        }

        return call_user_func($next, $request);
    }

    /**
     * Instantiate MaintenanceController and return its response by echo.
     * 
     * @param Request $request
     * 
     * @return void
     */
    private function callMaintenanceController(Request $request): void
    {
        $controller = new MaintenanceController(new View(),new FlashMessage());

        $controller->index($request)->sendResponse();
    }
}
