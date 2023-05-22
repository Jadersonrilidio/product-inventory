<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Middleware;

use DomainException;
use Jayrods\ProductInventory\Http\Core\Request;

class MiddlewareQueue
{
    /**
     * Middleware map in the form 'name' => 'class'.
     */
    private static array $map = [];

    /**
     * Default middlewares to be executed.
     */
    private static array $default = [];

    /**
     * Middlewares to be executed.
     */
    private array $middlewares;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->middlewares = self::$default;

        if (empty(self::$map)) {
            self::$map = (include CONFIG_DIR . 'middlewares.php') ?? [];
        }
    }

    /**
     * Set the middlewares map.
     * 
     * @param array $map
     * 
     * @return void
     */
    public static function setMap(array $map): void
    {
        self::$map = $map;
    }

    /**
     * Set default middlewares.
     * 
     * @param array $default
     * 
     * @return void
     */
    public static function setDefault(array $default): void
    {
        self::$default = $default;
    }

    /**
     * Add middlewares to be executed on queue.
     * 
     * @param array $middlewares
     * 
     * @return void
     */
    public function addMiddlewares(array $middlewares): void
    {
        array_push($this->middlewares, ...$middlewares);
    }

    /**
     * Execute the middleware on queue and call the next.
     * 
     * @param Request $request
     * 
     * @return bool Return TRUE on execution success or FALSE on failure.
     */
    public function next(Request $request): bool
    {
        if (empty($this->middlewares)) {
            return true;
        }

        $middleware = array_shift($this->middlewares);

        if (!isset(self::$map[$middleware])) {
            throw new DomainException("Problems to access Middleware's requisition.", 500);
        }

        $middleware = self::$map[$middleware];
        $middleware = new $middleware();

        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };

        return $middleware->handle($request, $next);
    }
}
