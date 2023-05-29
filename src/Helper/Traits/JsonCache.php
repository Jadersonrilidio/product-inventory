<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Helper\Traits;

trait JsonCache
{
    /**
     * Retrieve JSON cached files.
     *
     * By default, JSON cached files contains two fields:
     * 'timestamp' who controls if the cached data is still valid to use and
     * 'content', which contains the cached data ifself.
     *
     * @param string $file Cached file name.
     *
     * @return ?array Return NULL if the cache time expired.
     */
    public function getJsonCache(string $file): ?array
    {
        if (!$jsonCache = file_get_contents(CACHE_DIR . $file . '.json')) {
            return null;
        }

        $cache = json_decode($jsonCache, true);

        $expired = (time() - $cache['timestamp']) > CACHE_EXPIRATION_TIME;

        return !$expired ? $cache['content'] : null;
    }

    /**
     * Store JSON cached files.
     *
     * By default, JSON cached files contains two fields:
     * 'timestamp' who controls if the cached data is still valid to use and
     * 'content', which contains the cached data ifself.
     *
     * @param mixed  $content Content to cache.
     * @param string $file    Cached file name.
     *
     * @return mixed Return INT value if success or FALSE on failure.
     */
    public function storeJsonCache($content, string $file)
    {
        $cache = array(
            'timestamp' => time(),
            'content' => $content
        );

        $jsonContent = json_encode($cache);

        return file_put_contents(CACHE_DIR . $file . '.json', $jsonContent);
    }
}
