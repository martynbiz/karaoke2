<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

class HomeController extends BaseController
{
    /**
     * Show welcome screen
     */
    public function notFound($request, $response)
    {
        return $this->render('404')->withStatus(404);
    }
}
