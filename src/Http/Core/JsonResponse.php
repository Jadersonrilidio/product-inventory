<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Core;

class JsonResponse extends Response
{
    /**
     * Class constructor.
     * 
     * @param mixed $content
     * @param int $httpCode
     * @param string $contentType
     * @param array $headers
     */
    public function __construct(mixed $content, int $httpCode = 200, string $contentType = 'application/json', array $headers = [])
    {
        parent::__construct(json_encode($content), $httpCode, $contentType, $headers);
    }
}
