<?php
namespace MartynBiz\Slim\Module\Karaoke\View\Helper;

class BaseHelper
{
    /**
     * Slim\Container
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}
