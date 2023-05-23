<?php

declare(strict_types=1);

/**
 * Routes map, with keys containing the routes and values containing the parameters controller, method and middlewares to execute.
 */
return array(
    // Web Routes
    'GET|/' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'index'],
    'POST|/' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'deleteProduct'],
    'GET|/add-product' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'addProductPage'],
    'POST|/add-product' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'addProduct'],

    // API Routes
    'GET|/api/products' => [Jayrods\ProductInventory\Http\Controller\Api\ProductApiController::class, 'all'],
    'GET|/api/products/sku' => [Jayrods\ProductInventory\Http\Controller\Api\ProductApiController::class, 'skuList'],
    'GET|/api/products/type' => [Jayrods\ProductInventory\Http\Controller\Api\ProductApiController::class, 'productTypeList'],
    'POST|/api/products' => [Jayrods\ProductInventory\Http\Controller\Api\ProductApiController::class, 'store'],
    'POST|/api/products/mass-delete' => [Jayrods\ProductInventory\Http\Controller\Api\ProductApiController::class, 'removeMany'],

    // Web Fallback Route
    'fallback' => [Jayrods\ProductInventory\Http\Controller\ProductController::class, 'index'],

    // API Fallback Route
    'api-fallback' => [Jayrods\ProductInventory\Http\Controller\Api\ProductApiController::class, 'notFound']
);
