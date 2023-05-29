<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator;

use DomainException;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\AbstractProductValidator;
use Jayrods\ProductInventory\Http\Controller\Validator\Validator;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Infrastructure\FlashMessage;
use Jayrods\ProductInventory\Repository\ProductRepository\ProductRepository;

class ProductValidator implements Validator
{
    /**
     * FlashMessage instance.
     */
    private FlashMessage $flashMsg;

    /**
     * ProductRepository instance.
     */
    private ProductRepository $productRepository;

    /**
     * Class constructor.
     *
     * @param FlashMessage      $flashMsg
     * @param ProductRepository $productRepository
     */
    public function __construct(FlashMessage $flashMsg, ProductRepository $productRepository)
    {
        $this->flashMsg = $flashMsg;
        $this->productRepository = $productRepository;
    }

    /**
     * Instantiate a ProductValidator object according to given product type input and execute validation.
     *
     * @param Request $request
     *
     * @return bool Return TRUE if inputs are valid, FALSE otherwise.
     */
    public function validate(Request $request): bool
    {
        $specificProductValidator = $this->create($request);

        return $specificProductValidator->validate($request);
    }

    /**
     * Factory method. Instantiate a AbstractProductValidator object according to given product type input.
     *
     * @param Requet $request
     *
     * @return AbstractProductValidator
     */
    private function create(Request $request): AbstractProductValidator
    {
        if (!$this->typeExists($request->inputs('type'))) {
            throw new DomainException("Product type doesn't exists.");
        }

        $validatorClass = __NAMESPACE__ . "\\" . $request->inputs('type') . "Validator";

        return new $validatorClass($this->flashMsg, $this->productRepository);
    }

    /**
     * Check whether product type exists.
     *
     * @param string $type
     *
     * @return bool
     */
    private function typeExists(string $type): bool
    {
        if (!in_array($type, $this->productRepository->getEnumTypes(), true)) {
            return false;
        }

        return true;
    }
}
