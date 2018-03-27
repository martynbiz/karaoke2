<?php
namespace App\Controller\Console;

use App\Controller\BaseController;

class HomeController extends BaseController
{
    /**
     * Homepage
     */
    public function index($request, $response, $args)
    {
        return $this->render('console/home/index');
    }
}
