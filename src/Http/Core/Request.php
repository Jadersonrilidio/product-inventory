<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Core;

class Request
{
    /**
     * Request HTTP method.
     */
    private string $httpMethod;

    /**
     * Request Content-Type.
     */
    private string $contentType;

    /**
     * Request URI.
     */
    private string $uri;

    /**
     * Array containing request's headers.
     */
    private array $headers = [];

    /**
     * Array containing request URI parameters, if any.
     * 
     * URI params are available in the form 'key' => 'value', where the 'key' is the parameter name given by the route.
     */
    private array $uriParams = [];

    /**
     * Array containing request query parameters, if any.
     */
    private array $queryParams = [];

    /**
     * Array containing request POST parameters, if any.
     */
    private array $inputs = [];

    /**
     * Array containing request files, if any.
     */
    private array $files = [];

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->contentType = $_SERVER['CONTENT_TYPE'] ?? 'text/html';
        $this->uri = $this->sanitizedUri();
        $this->headers = getallheaders();
        $this->files = $this->handleFiles($_FILES);
        $this->handleQueryParams();
        $this->handlePostVars();
    }

    /**
     * Add request URI parameters, if any;
     * 
     * @param array $keys URI parameters' names, given by the route.
     * @param array $values URI parameters' values.
     * 
     * @return void
     */
    public function addUriParams(array $keys, array $values): void
    {
        $this->handleUriParams(array_combine($keys, $values));
    }

    /**
     * Sanitize and add the URI parameters.
     * 
     * If the sanitizing process fails or the value has only whitespace-like characters, the given URI parameter is not added.
     * 
     * @param array $params In the form "name" => "value".
     * 
     * @return void
     */
    private function handleUriParams(array $params): void
    {
        foreach ($params as $key => $value) {
            $var = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
            if (!is_null($var) and !ctype_space($var)) {
                $this->uriParams[$key] = $var;
            }
        }
    }

    /**
     * Return the URI sanitized by 'filter_input' function.
     * 
     * @return string
     */
    private function sanitizedUri(): string
    {
        return isset($_SERVER['PATH_INFO'])
            ? filter_input(INPUT_SERVER, 'PATH_INFO', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE) ?? '/'
            : '/';
    }

    /**
     * Sanitize and add query parameters.
     * 
     * If the sanitizing process fails or the value has only whitespace-like characters, the given query parameter is not added.
     * 
     * @return void
     */
    private function handleQueryParams(): void
    {
        $paramKeys = array_keys($_GET);

        foreach ($paramKeys as $param) {
            $queryParam = filter_input(INPUT_GET, $param, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
            if (!is_null($queryParam) and !ctype_space($queryParam)) {
                $this->queryParams[$param] = $queryParam;
            }
        }
    }

    /**
     * Sanitize and add POST variables to 'input' object property.
     * 
     * If the sanitizing process fails or the value has only whitespace-like characters, the given POST variable is not added.
     * 
     * @return void
     */
    private function handlePostVars(): void
    {
        $paramKeys = array_keys($_POST);

        foreach ($paramKeys as $param) {
            $postVar = filter_input(INPUT_POST, $param, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
            if (!is_null($postVar) and !ctype_space($postVar)) {
                $this->inputs[$param] = $postVar;
            }
        }
    }

    /**
     * Return array of files.
     * 
     * Each file has an unique 'hash' field added for storage purposes. If no file is found, return an empty array.
     * 
     * @param ?array $files The files to be added, could be the $_FILES global variable or a custom form, provided the file array structure data is maintained.
     * 
     * @return array
     */
    private function handleFiles(?array $files = null): array
    {
        if (empty($files) or is_null($files)) {
            return [];
        }

        $files = array_map(function ($file) {
            $extension = explode('.', $file['name'])[1];
            $hashedName = 'upload_' . hash('md5', uniqid() . time());

            $file['hash'] = $hashedName . '.' . $extension;

            return $file;
        }, $files);

        return $files ?? [];
    }

    /**
     * Get request HTTP method.
     * 
     * @return string
     */
    public function httpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Get request URI.
     * 
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get request Content-Type.
     * 
     * @return string
     */
    public function contentType(): string
    {
        return $this->contentType;
    }

    /**
     * Return a header value or the request headers array if no argument is provided, return null if the header is not found.
     * 
     * @param string $param The header to return.
     * 
     * @return array|string|null
     */
    public function headers(?string $param = null): array|string|null
    {
        if (is_null($param)) {
            return $this->headers;
        }

        return isset($this->headers[$param]) ? $this->headers[$param] : null;
    }

    /**
     * Return an URI parameter value or an array with URI parameters if no argument is provided, return null if the URI parameter is not found.
     * 
     * @param string $param The URI parameter to return.
     * 
     * @return mixed
     */
    public function uriParams(?string $param = null): mixed
    {
        if (!is_null($param)) {
            return $this->uriParams;
        }

        return isset($this->uriParams[$param]) ? $this->uriParams[$param] : null;
    }

    /**
     * Return a query param value or an array with query params if no argument is provided, return null if the query param is not found.
     * 
     * @param string $param The query param to return.
     * 
     * @return mixed
     */
    public function queryParams(string $param = null): mixed
    {
        if (is_null($param)) {
            return $this->queryParams;
        }

        return isset($this->queryParams[$param]) ? $this->queryParams[$param] : null;
    }

    /**
     * Return an input value or an array with all inputs if no argument is provided, return null if the input is not found.
     * 
     * @param string $param The input to return.
     * 
     * @return mixed
     */
    public function inputs(string $param = null): mixed
    {
        if (is_null($param)) {
            return $this->inputs;
        }

        return isset($this->inputs[$param]) ? $this->inputs[$param] : null;
    }

    /**
     * Return a file data or an array with all files if no argument is provided, return null if the file is not found.
     * 
     * @param string $param The filename to return.
     * 
     * @return mixed
     */
    public function files(string $param = null): mixed
    {
        if (is_null($param)) {
            return $this->files;
        }

        return isset($file[$param]) ? $file[$param] : null;
    }
}
