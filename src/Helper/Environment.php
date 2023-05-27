<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Helper;

class Environment
{
    /**
     * Return the environment variable value or the default value if not found.
     * 
     * @param string $varName
     * @param mixed $default
     * 
     * @return mixed
     */
    public static function env(string $varName, $default = null)
    {
        return isset($_ENV[$varName]) ? $_ENV[$varName] : $default;
    }
}
