<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Console;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

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
