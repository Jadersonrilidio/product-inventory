<?php

declare(strict_types=1);

/**
 * Return the environment variable value or the default value if not found.
 * 
 * @return mixed
 */
function env(string $varName, mixed $default = null): mixed
{
    return isset($_ENV[$varName]) ? $_ENV[$varName] : $default;
}
