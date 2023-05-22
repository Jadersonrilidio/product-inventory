<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator;

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\AbstractProductValidator;

class FurnitureValidator extends AbstractProductValidator
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
            'height' => $this->validateHeight($request->inputs('height')),
            'width' => $this->validateWidth($request->inputs('width')),
            'length' => $this->validateLength($request->inputs('length'))
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
            'height-value' => $request->inputs('height'),
            'width-value' => $request->inputs('width'),
            'length-value' => $request->inputs('length')
        ));
    }

    /**
     * Height input validation.
     * 
     * @param string $height
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateHeight(string $height): bool
    {
        if (!is_numeric($height)) {
            $this->flashMsg->add(array(
                'height-error' => "Invalid height format (must be numeric integer)."
            ));
            return false;
        }

        return true;
    }

    /**
     * Width input validation.
     * 
     * @param string $width
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateWidth(string $width): bool
    {
        if (!is_numeric($width)) {
            $this->flashMsg->add(array(
                'width-error' => "Invalid width format (must be numeric integer)."
            ));
            return false;
        }

        return true;
    }

    /**
     * Length input validation.
     * 
     * @param string $length
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateLength(string $length): bool
    {
        if (!is_numeric($length)) {
            $this->flashMsg->add(array(
                'length-error' => "Invalid length format (must be numeric integer)."
            ));
            return false;
        }

        return true;
    }
}
