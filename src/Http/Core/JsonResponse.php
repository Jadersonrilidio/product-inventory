<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Core;

class JsonResponse extends Response
{
    /**
     * CORS Policy headers values in the form of associative array.
     */
    private static array $cors;

    /**
     * Class constructor.
     *
     * @param mixed  $content
     * @param int    $httpCode
     * @param string $contentType
     * @param array  $headers
     */
    public function __construct(
        $content,
        int $httpCode = 200,
        string $contentType = 'application/json',
        array $headers = []
    ) {
        parent::__construct(json_encode($content), $httpCode, $contentType, $headers);

        $this->addCORSPolicyToHeaders();
    }

    /**
     * Set default CORS Policy headers to be added on every API route.
     *
     * @param array $corsPolicy
     *
     * @return void
     */
    public static function setApiCORSPolicy(array $corsPolicy): void
    {
        self::$cors = $corsPolicy;
    }

    /**
     * Add CORS-Policy to headers.
     *
     * @return void
     */
    protected function addCORSPolicyToHeaders(): void
    {
        $this->headers['Access-Control-Allow-Origin'] = self::$cors['origins'];
        $this->headers['Access-Control-Allow-Methods'] = self::$cors['methods'];
        $this->headers['Access-Control-Allow-Headers'] = self::$cors['headers'];
    }
}
