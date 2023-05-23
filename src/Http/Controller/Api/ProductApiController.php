<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller\Api;

use Jayrods\ProductInventory\Http\Controller\Api\ApiController;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\ProductValidator;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\JsonResponse;
use Jayrods\ProductInventory\Repository\ProductRepository\ProductRepository;

class ProductApiController extends ApiController
{
    /**
     * Entity classes base namespace.
     */
    private const ENTITY_CLASS_NAMESPACE = "Jayrods\\ProductInventory\\Entity\\";

    /**
     * ProductRepository instance.
     */
    private ProductRepository $productRepository;

    /**
     * ProductValidator instance.
     */
    private ProductValidator $productValidator;

    /**
     * Class constructor
     * 
     * @param ProductRepository $productRepository
     * @param ProductValidator $productValidator
     */
    public function __construct(ProductRepository $productRepository, ProductValidator $productValidator)
    {
        $this->productRepository = $productRepository;
        $this->productValidator = $productValidator;
    }

    /**
     * Route: GET|/api/products.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $products = $this->productRepository->all();

        return new JsonResponse($products, 200, 'application/json');
    }

    /**
     * Route: POST|/api/products.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->productValidator->validate($request)) {
            return new JsonResponse(['error' => 'invalid input data.'], 400, 'application/json');
        }

        $inputs = $request->inputs();

        $type = $inputs['type'];
        unset($inputs['type']);

        $data = array_map(function ($input) {
            return is_numeric($input) ? (int) $input : $input;
        }, $inputs);

        $class = self::ENTITY_CLASS_NAMESPACE . $type;

        $product = new $class(...$data);

        $result = $this->productRepository->save($product);

        return $result
            ? new JsonResponse($product, 200, 'application/json')
            : new JsonResponse(['error' => 'error on saving product.'], 400, 'application/json');
    }

    /**
     * Route: POST|/api/products/mass-delete.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function removeMany(Request $request): JsonResponse
    {
        $deleteList = explode(',', $request->inputs('sku-list'));

        $result = $this->productRepository->removeManyBySku(...$deleteList);

        return $result
            ? new JsonResponse(['message' => 'products removed with success'], 200, 'application/json')
            : new JsonResponse(['error' => 'error on removing products'], 400, 'application/json');
    }

    /**
     * Route: GET|/api/products/sku.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function skuList(Request $request): JsonResponse
    {
        $skuList = $this->productRepository->skuList();

        return new JsonResponse($skuList, 200, 'application/json');
    }

    /**
     * Route: GET|/api/products/type.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function productTypeList(Request $request): JsonResponse
    {
        $productTypeList = $this->productRepository->getEnumTypes();

        return new JsonResponse($productTypeList, 200, 'application/json');
    }

    /**
     * Route: api-fallback.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function notFound(Request $request): JsonResponse
    {
        return new JsonResponse(['error' => 'not found'], 404, 'application/json');
    }
}
