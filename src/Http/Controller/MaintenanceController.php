<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller;

use Jayrods\ProductInventory\Http\Controller\Controller;
use Jayrods\ProductInventory\Http\Core\Request;
use Jayrods\ProductInventory\Http\Core\Response;

class MaintenanceController extends Controller
{
    /**
     * Executed whenever app is under maintenance.
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pageContent = $this->view->renderView('maintenance');

        $page = $this->view->renderTemplate('App Maintenance', $pageContent);

        return new Response($page);
    }
}
