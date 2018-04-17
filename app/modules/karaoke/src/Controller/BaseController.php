<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller;

use Slim\Container;
use MartynBiz\Slim\Module\Auth\Traits\GetCurrentUser;
use MartynBiz\Slim\Module\Karaoke\Model\Song;

class BaseController extends \MartynBiz\Slim\Module\Core\Controller
{
    use GetCurrentUser;

    /**
     * Get the current sign in user user
     * @param Request $request Not really needed here, api uses it though
     * @return User|null
     */
    protected function getCurrentPlaylist()
    {
        $container = $this->getContainer();
        return $container['playlist'];
    }

    // /**
    //  * Render the html and attach to the response
    //  * @param string $file Name of the template/ view to render
    //  * @param array $args Additional variables to pass to the view
    //  * @param Response?
    //  */
    // protected function render($file, $data=array())
    // {
    //     $data['playlist'] = $this->getCurrentPlaylist();
    //
    //     return parent::render($file, $data);
    // }
}
