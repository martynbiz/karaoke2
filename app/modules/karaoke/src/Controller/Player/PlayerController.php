<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Player;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

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
