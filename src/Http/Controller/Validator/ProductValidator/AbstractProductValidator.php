<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator;

use Jayrods\ProductInventory\Http\Controller\Validator\Validator;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Infrastructure\FlashMessage;
use Jayrods\ProductInventory\Repository\ProductRepository\ProductRepository;

abstract class AbstractProductValidator implements Validator
{
    /**
     * FlashMessage instance.
     */
    protected FlashMessage $flashMsg;

    /**
     * ProductRepository instance.
     */
    protected ProductRepository $productRepository;

    /**
     * Class constructor.
     * 
     * @param FlashMessage $flashMsg
     * @param ProductRepository $productRepository
     */
    public function __construct(FlashMessage $flashMsg, ProductRepository $productRepository)
    {
        $this->flashMsg = $flashMsg;
        $this->productRepository = $productRepository;
    }

    /**
     * Execute validation of input data and uploaded files.
     * 
     * @param Request $request
     * 
     * @return bool Return TRUE if inputs are valid, FALSE otherwise.
     */
    public function validate(Request $request): bool
    {
        return $this->check(array(
            'sku' => $this->validateSku($request->inputs('sku')),
            'name' => $this->validateName($request->inputs('name')),
            'price' => $this->validatePrice($request->inputs('price')),
            'type' => $this->validateType($request->inputs('type'))
        ));
    }

    /**
     * Check the validation array.
     * 
     * @param array $validation
     * 
     * @return bool Return TRUE if all validation passes, FALSE otherwise.
     */
    protected function check(array $validation): bool
    {
        foreach ($validation as $value) {
            if (!$value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Actions to be executed on validation success.
     * 
     * @param Request $request
     * 
     * @return void
     */
    abstract protected function onSuccess(Request $request): void;

    /**
     * Actions to be executed on validation fail.
     * 
     * @param Request $request
     * 
     * @return void
     */
    abstract protected function onFail(Request $request): void;

    /**
     * SKU input validation.
     * 
     * @param string $sku
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateSku(string $sku): bool
    {
        $result = true;

        if (!preg_match('/^[a-zA-Z0-9]{1,32}$/', $sku)) {
            $this->flashMsg->add(array(
                'sku-error' => "Invalid SKU format (must have at least 8 to 32 alphanumeric characters)."
            ));
            $result = false;
        }

        if ($this->productRepository->skuExists($sku)) {
            $this->flashMsg->add(array(
                'sku-error' => "SKU already exists."
            ));
            $result = false;
        }

        return $result;
    }

    /**
     * Name input validation.
     * 
     * @param string $name
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateName(string $name): bool
    {
        $result = true;

        if (!preg_match('/^[a-zA-Z\s0-9]+$/', $name)) {
            $this->flashMsg->add(array(
                'name-error' => "Invalid name format (must contain only alphanumeric characters)."
            ));
            $result = false;
        }

        if (strlen($name) > 128) {
            $this->flashMsg->add(array(
                'name-error' => "Name must have no more than 128 characters."
            ));
            $result = false;
        }

        return $result;
    }

    /**
     * Price input validation.
     * 
     * @param string $price
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validatePrice(string $price): bool
    {
        if (!is_numeric($price)) {
            $this->flashMsg->add(array(
                'price-error' => "Invalid price format (must be numeric integer)."
            ));
            return false;
        }

        return true;
    }

    /**
     * Type input validation.
     * 
     * @param string $type
     * 
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateType(string $type): bool
    {
        if (!in_array($type, $this->productRepository->getEnumTypes(), true)) {
            $this->flashMsg->add(array(
                'type-error' => "Invalid product type."
            ));
            return false;
        }

        return true;
    }
}
