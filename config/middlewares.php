<?php

declare(strict_types=1);

/**
 * Mapping of Middleware and representing Class.
 */
return array(
    'maintenance' => Jayrods\ProductInventory\Http\Middleware\MaintenanceMiddleware::class,
    'session' => Jayrods\ProductInventory\Http\Middleware\SessionMiddleware::class
);
