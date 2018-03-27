<?php
namespace App\Controller\Player;

use App\Controller\BaseController;

class PlayerController extends BaseController
{
    /**
     * Homepage
     */
    public function index($request, $response, $args)
    {
        return $this->render('player/player/index');
    }
}
