<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Console;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

class TagsController extends BaseController
{
    /**
     * Tags
     */
    public function index($request, $response, $args)
    {
        $container = $this->getContainer();
        $tags = $container->get('model.tag')
            ->where('is_valid', 1)
            ->orderBy('name')
            ->get();

        return $this->render('console/tags/index', [
            'tags' => $tags,
        ]);
    }

    /**
     * edit transaction form
     */
    public function view($request, $response, $args)
    {
        $container = $this->getContainer();
        $params = $request->getQueryParams();

        $tag = $container->get('model.tag')->findOrFail((int)$args['tag_id']);

        return $this->render('console/tags/view', [
            // 'artist' => $artist,
            'songs' => $tag->songs,
            'tag' => $tag,
            'params' => $params,
        ]);
    }
}
