<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator;

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\AbstractProductValidator;

class DVDValidator extends AbstractProductValidator
{
    /**
     * Execute validation of input data and uploaded files.
     * 
     * @param Request $request
     * 
     * @return bool Return TRUE if inputs are valid, FALSE otherwise.
     */
    public function validate(Request $request): bool
    {
        $validation =  $this->check(array(
            'product' => parent::validate($request),
            'size' => $this->validateSize($request->inputs('size'))
        ));

        $validation ? $this->onSuccess($request) : $this->onFail($request);

        return $validation;
    }

    /**
     * Actions to be executed on validation success.
     * 
     * @param Request $request
     * 
     * @return void
     */
    protected function onSuccess(Request $request): void
    {
        //
    }

    /**
     * Actions to be executed on validation fail.
     * 
     * @param Request $request
     * 
     * @return void
     */
    protected function onFail(Request $request): void
    {
        $this->flashMsg->add(array(
            'sku-value' => $request->inputs('sku'),
            'name-value' => $request->inputs('name'),
            'price-value' => $request->inputs('price'),
            'type-value' => $request->inputs('type'),
            'size-value' => $request->inputs('size')
        ));
    }

    /**
     * Size input validation.
     * 
     * @param string $Size
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateSize(string $size): bool
    {
        if (!is_numeric($size)) {
            $this->flashMsg->add(array(
                'size-error' => "Invalid size format (must be numeric integer)."
            ));
            return false;
        }

        return true;
    }
}
