<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Core;

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\Response;
use Jayrods\ProductInventory\Http\Middleware\MiddlewareQueue;
use Jayrods\ProductInventory\Helper\Traits\JsonCache;
use Psr\Container\ContainerInterface;

class Router
{
    use JsonCache;

    /**
     * Cached route => regex map filename.
     */
    private const ROUTE_REGEX_CACHE_NAME = 'routeRegexMap';

    /**
     * Request instance.
     */
    private Request $request;

    /**
     * MiddlewareQueue instance.
     */
    private MiddlewareQueue $middlewareQueue;

    /**
     * Dependency Injection Container instance.
     */
    private ContainerInterface $diContainer;

    /**
     * Mapping of route to its parameters in the form "route" => "[controller, method, middlewares]".
     */
    private array $routes;

    /**
     * Class constructor.
     *
     * @param Request            $request         Instance of Request.
     * @param MiddlewareQueue    $middlewareQueue Instance of MiddlewareQueue.
     * @param ContainerInterface $diContainer     Instance of Container for Dependency Injection.
     * @param array              $routes          Map of "route" => "[controller, method, middlewares]"
     */
    public function __construct(
        Request $request,
        MiddlewareQueue $middlewareQueue,
        ContainerInterface $diContainer,
        array $routes
    ) {
        $this->request = $request;
        $this->middlewareQueue = $middlewareQueue;
        $this->diContainer = $diContainer;
        $this->routes = $routes;
    }

    /**
     * Handle the request, call the appropriate controller/method and return a Response.
     *
     * @return Response
     */
    public function handleRequest(): Response
    {
        $routeParams = $this->routeParams();

        $controller = $routeParams[0];
        $method = $routeParams[1];
        $middlewares = $routeParams[2] ?? [];

        $this->executeMiddlewaresQueue($middlewares);

        $controller = $this->diContainer->get($controller);

        return $controller->$method($this->request);
    }

    /**
     * Return the requested route parameters.
     *
     * @return array The route param array in the form ['string:controller', 'string:method', 'array:middlewares'].
     */
    private function routeParams(): array
    {
        $httpMethod = $this->request->httpMethod();
        $uri = $this->request->uri();

        $routeRegexArray = $this->getJsonCache(self::ROUTE_REGEX_CACHE_NAME) ?? $this->createRouteRegexArray();

        $requestedRoute = "$httpMethod|$uri";

        foreach ($routeRegexArray as $route => $regex) {
            if (preg_match($regex, $requestedRoute, $uriParamValues)) {
                if (preg_match_all('/\{([^\/]+?)\}/', $route, $uriParamKeys)) {
                    unset($uriParamValues[0]);

                    $this->request->addUriParams($uriParamKeys[1], $uriParamValues);
                }

                return $this->routes[$route];
            }
        }

        return preg_match('/^\/api(.*?)$/', $uri) === 1 ? $this->routes['api-fallback'] : $this->routes['fallback'];
    }

    /**
     * Create a map in the form "route" => "regex".
     *
     * @return array
     */
    private function createRouteRegexArray(): array
    {
        // Mount route-regex array structure
        $regexArray = array_combine(array_keys($this->routes), array_keys($this->routes));

        // Replace URI params by regex group
        $regexArray = preg_replace('/\{.+?\}/', '([^/]+?)', $regexArray);

        // Format regex expression slashes
        $regexArray = str_replace('/', '\/', $regexArray);

        // Format regex expression slashes
        $regexArray = str_replace('|', '\|', $regexArray);

        // wrap regex expression with start and end signs
        $regexArray = array_map(
            function ($route) {
                return '/^' . $route . '$/';
            },
            $regexArray
        );

        $this->storeJsonCache($regexArray, self::ROUTE_REGEX_CACHE_NAME);

        return $regexArray;
    }

    /**
     * Call the middlewares queue to execute.
     *
     * @param array $middlewares The route specific middlewares to be added to middleware queue.
     *
     * @return bool
     */
    private function executeMiddlewaresQueue(array $middlewares): bool
    {
        $this->middlewareQueue->addMiddlewares($middlewares);

        return $this->middlewareQueue->next($this->request);
    }

    /**
     * Redirect to the specified path. If empty path if provided, redirect to "/" route.
     *
     * @param string $path URI path to redirect.
     *
     * @return void
     */
    public static function redirect(string $path = ''): void
    {
        header("Location: " . SLASH . $path);
        exit;
    }
}
