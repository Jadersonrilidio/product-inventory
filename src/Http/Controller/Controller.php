<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Controller;

use Jayrods\ProductInventory\Http\Core\View;
use Jayrods\ProductInventory\Infrastructure\FlashMessage;

abstract class Controller
{
    /**
     * View object instance.
     */
    protected View $view;

    /**
     * FlashMessage object instance.
     */
    protected FlashMessage $flashMsg;

    /**
     * Class constructor.
     *
     * @param View         $view     Instance of View.
     * @param FlashMessage $flashMsg Instance of FlashMessage.
     */
    public function __construct(View $view, FlashMessage $flashMsg)
    {
        $this->view = $view;
        $this->flashMsg = $flashMsg;
    }
}
