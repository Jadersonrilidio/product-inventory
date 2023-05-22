<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller;

use Jayrods\ProductInventory\Http\Controller\Controller;
use Jayrods\ProductInventory\Http\Controller\Validator\ProductValidator\ProductValidator;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\Response;
use Jayrods\ProductInventory\Http\Core\Router;
use Jayrods\ProductInventory\Http\Core\View;
use Jayrods\ProductInventory\Infrastructure\FlashMessage;
use Jayrods\ProductInventory\Repository\ProductRepository\ProductRepository;

class ProductController extends Controller
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
     * ProductValidator instance
     */
    private ProductValidator $productValidator;

    /**
     * Class constructor.
     * 
     * @param View $view
     * @param FlashMessage $flashMsg
     * @param ProductRepository $productRepository
     * @param ProductValidatorFactory $productValidatorFactory
     */
    public function __construct(View $view, FlashMessage $flashMsg, ProductRepository $productRepository, ProductValidator $productValidator)
    {
        parent::__construct($view, $flashMsg);

        $this->productRepository = $productRepository;
        $this->productValidator = $productValidator;
    }

    /**
     * Route: GET|/.
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function index(Request $request): Response
    {
        $productsContent = '';

        foreach ($this->productRepository->all() as $product) {
            $productsContent .= $this->view->renderComponent('product-container', array(
                'sku' => $product->sku(),
                'name' => $product->name(),
                'price' => $product->formatedPrice(),
                'specific-attribute' => $product->formatedSpecificAttributes()
            ));
        }

        $pageContent = $this->view->renderView('product-list', [
            'app-url' => APP_URL,
            'products' => $productsContent,
        ]);

        $page = $this->view->renderTemplate('Product List', $pageContent);

        return new Response($page, 200, 'text/html');
    }

    /**
     * Route: POST|/.
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function deleteProduct(Request $request): Response
    {
        $deleteList = explode(',', $request->inputs('delete-list'));
        
        $this->productRepository->removeManyBySku(...$deleteList);

        Router::redirect();
        exit;
    }

    /**
     * Route: GET|/add-product.
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function addProductPage(Request $request): Response
    {
        $pageContent = $this->view->renderView('product-add', [
            'app-url' => APP_URL,
            'input-validation-error' => $this->flashMsg->get('sku-error') ?? ''
        ]);

        $page = $this->view->renderTemplate('Product Add', $pageContent);

        return new Response($page, 200, 'text/html');
    }

    /**
     * Route: POST|/add-product.
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function addProduct(Request $request): Response
    {
        if (!$this->productValidator->validate($request)) {
            Router::redirect('add-product');
        }

        $inputs = $request->inputs();

        $type = $inputs['type'];
        unset($inputs['type']);

        $inputs = array_map(function ($input) {
            return is_numeric($input) ? (int) $input : $input;
        }, $inputs);
        
        $class = self::ENTITY_CLASS_NAMESPACE . $type;

        $product = new $class(...$inputs);

        $this->productRepository->save($product);

        Router::redirect();
        exit;
    }
}
