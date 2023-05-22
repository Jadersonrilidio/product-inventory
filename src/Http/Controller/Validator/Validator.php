<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Validator;

use Jayrods\ProductInventory\Http\Core\Request;

interface Validator
{
    /**
     * Execute validation of input data and uploaded files.
     * 
     * @param Request $request
     * 
     * @return bool Return TRUE if inputs are valid, FALSE otherwise.
     */
    public function validate(Request $request): bool;
}
