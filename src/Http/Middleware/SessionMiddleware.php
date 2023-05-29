<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Middleware;

use Closure;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Middleware\Middleware;

class SessionMiddleware implements Middleware
{
    /**
     * Handle middleware execution.
     *
     * @param Request $request
     * @param Closure $next    The next middleware to be executed in queue.
     *
     * @return bool
     */
    public function handle(Request $request, Closure $next): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        return call_user_func($next, $request);
    }
}
