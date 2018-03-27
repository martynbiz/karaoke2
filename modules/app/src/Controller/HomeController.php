<?php
namespace App\Controller;

use App\Controller\BaseController;

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
