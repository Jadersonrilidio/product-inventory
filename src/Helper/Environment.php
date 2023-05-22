<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Helper;

class Environment
{
    /**
     * Return the environment variable value or the default value if not found.
     * 
     * @return mixed
     */
    public static function env(string $varName, mixed $default = null): mixed
    {
        return isset($_ENV[$varName]) ? $_ENV[$varName] : $default;
    }
}
