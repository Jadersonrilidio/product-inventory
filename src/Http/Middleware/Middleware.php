<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Middleware;

use Closure;
use Jayrods\ProductInventory\Http\Core\Request;

interface Middleware
{
    /**
     * Handle middleware execution.
     * 
     * @param Request $request
     * @param Closure $next The next middleware to be executed in queue.
     * 
     * @return bool
     */
    public function handle(Request $request, Closure $next): bool;
}
