<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Core;

class Response
{
    /**
     * Response HTTP code.
     */
    protected int $httpCode;

    /**
     * Response content body.
     */
    protected string $content;

    /**
     * Response Content-Type.
     */
    protected string $contentType;

    /**
     * Response headers.
     */
    protected array $headers;

    /**
     * Class constructor.
     *
     * @param string $content
     * @param int    $httpCode
     * @param string $contentType
     * @param array  $headers
     */
    public function __construct(
        string $content,
        int $httpCode = 200,
        string $contentType = 'text/html',
        array $headers = []
    ) {
        $this->content = $content;
        $this->contentType = $contentType;
        $this->httpCode = $httpCode;
        $this->headers = $headers;
        $this->addContentTypeToHeaders();
    }

    /**
     * Send Response.
     *
     * @return void
     */
    public function sendResponse(): void
    {
        http_response_code($this->httpCode);

        $this->sendHeaders();

        echo $this->content;

        exit;
    }

    /**
     * Add Content-Type to Response headers.
     *
     * @return void
     */
    protected function addContentTypeToHeaders(): void
    {
        $this->headers['Content-Type'] = $this->contentType;
    }

    /**
     * Send headers.
     *
     * @return void
     */
    protected function sendHeaders(): void
    {
        foreach ($this->headers as $key => $value) {
            header("$key: $value", true);
        }
    }
}
