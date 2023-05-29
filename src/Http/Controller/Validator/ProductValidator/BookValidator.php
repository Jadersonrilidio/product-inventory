<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator;

use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\AbstractProductValidator;

class BookValidator extends AbstractProductValidator
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
        return $this->check(
            array(
                'product' => parent::validate($request),
                'weight' => $this->validateWeight($request->inputs('weight'))
            ),
            $request
        );
    }

    /**
     * Actions to be executed on validation success.
     *
     * @param Request $request
     *
     * @return bool Return TRUE.
     */
    protected function onSuccess(Request $request): bool
    {
        return true;
    }

    /**
     * Actions to be executed on validation fail.
     *
     * @param Request $request
     *
     * @return bool Return FALSE.
     */
    protected function onFail(Request $request): bool
    {
        $this->flashMsg->add(
            array(
                'sku-value' => $request->inputs('sku'),
                'name-value' => $request->inputs('name'),
                'price-value' => $request->inputs('price'),
                'type-value' => $request->inputs('type'),
                'weight-value' => $request->inputs('weight')
            )
        );

        return false;
    }

    /**
     * Weight input validation.
     *
     * @param string $weight
     *
     * @return bool TRUE on success or FALSE if validation fails.
     */
    public function validateWeight(string $weight): bool
    {
        if (!is_numeric($weight)) {
            $this->flashMsg->add(
                array(
                    'weight-error' => "Invalid weight format (must be numeric integer)."
                )
            );
            return false;
        }

        return true;
    }
}
