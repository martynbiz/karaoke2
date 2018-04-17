<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Console;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

class SongsController extends BaseController
{
    /**
     * Homepage
     */
    public function index($request, $response, $args)
    {
        $container = $this->getContainer();
        $params = $request->getQueryParams();

        $page = (int)$request->getQueryParam('page', 1);
        $limit = (int)$request->getQueryParam('limit', 20);
        $start = ($page-1) * $limit;
        $orderBy = $request->getQueryParam('orderby', 'artist_id');
        $orderDir = $request->getQueryParam('orderby', 'asc');

        // get total transactions for calculating pagination
        $totalSongs = $container->get('model.song')->count();
        $totalPages = ($totalSongs > 0) ? ceil($totalSongs / $limit) : 1; // TODO 0?

        // get paginated transactions for dispaying in the table
        $baseQuery = $container->get('model.song')
            ->with('artist')
            // ->with('meta')
            ->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($limit);

        // query string
        if ($q = @$params['query']) {
            $baseQuery->where('name', 'LIKE', "%$q%");
        }

        $songs = $baseQuery->get();

        return $this->render('console/songs/index', [
            'songs' => $songs,
            'query' => $q,

            // pagination
            'total_pages' => $totalPages,
            'page' => $page,
        ]);
    }

    /**
     * Most requested
     */
    public function mostRequested($request, $response, $args)
    {
        $container = $this->getContainer();

        // get paginated transactions for dispaying in the table
        $songs = $container->get('model.song')
            ->with('artist')
            ->orderBy('total_requests', 'desc')
            ->take(20)
            ->get();

        return $this->render('console/songs/most_requested', [
            'songs' => $songs,
        ]);
    }

    /**
     * edit song form
     */
    public function view($request, $response, $args)
    {
        $container = $this->getContainer();

        $song = $container->get('model.song')->findOrFail((int)$args['song_id']);

        return $this->render('console/songs/view', [
            'song' => $song,
        ]);
    }
}
