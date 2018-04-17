<?php
namespace MartynBiz\Slim\Module\Karaoke\View\Helper;

class Translate extends BaseHelper
{
    function __invoke($str)
    {
        return $this->container['i18n']->translate($str);
    }
}
