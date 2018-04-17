<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Console;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

class ArtistsController extends BaseController
{
    /**
     * Homepage > Artists
     */
    public function index($request, $response, $args)
    {
        $container = $this->getContainer();
        $params = $request->getQueryParams();

        $params = array_merge([
            'start' => 0,
            'limit' => 50,
            'orderby' => 'name',
            'dir' => 'asc',
        ], $request->getQueryParams());

        $query = $container->get('model.artist')
            ->with('meta')
            ->orderBy( $params['orderby'], $params['dir'] );
            // ->skip( (int)$params['start'] )
            // ->take( (int)$params['limit'] );

        // query string
        if ($q = @$params['query']) {
            $query->where('name', 'LIKE', "%$q%");
        }

        $artists = $query->get();

        $groups = [];
        foreach ($artists as $artist) {
            $letter = substr($artist->name, 0, 1);

            // if any number, just group under "#"
            if (is_numeric($letter)) $letter = '#';

            // create empty array if no already done so
            if (!isset($groups[$letter])) $groups[$letter] = [];

            array_push($groups[$letter], $artist);
        }

        return $this->render('console/artists/index', [
            'groups' => $groups,
            'params' => $params,
        ]);
    }

    /**
     * edit transaction form
     */
    public function view($request, $response, $args)
    {
        $container = $this->getContainer();
        $params = $request->getQueryParams();

        $artist = $container->get('model.artist')->findOrFail((int)$args['artist_id']);

        return $this->render('console/artists/view', [
            'artist' => $artist,
            'songs' => $artist->songs,
            'params' => $params,
        ]);
    }
}
