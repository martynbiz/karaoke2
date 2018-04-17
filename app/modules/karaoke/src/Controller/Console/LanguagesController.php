<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Console;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

class LanguagesController extends BaseController
{
    /**
     * Languages
     */
    public function index($request, $response, $args)
    {
        $container = $this->getContainer();
        $languages = $container->get('model.language')
            ->orderBy('name')
            ->get();

        return $this->render('console/languages/index', [
            'languages' => $languages,
        ]);
    }

    /**
     * edit transaction form
     */
    public function view($request, $response, $args)
    {
        $container = $this->getContainer();

        $language = $container->get('model.language')->findOrFail((int)$args['language_id']);

        $page = (int)$request->getQueryParam('page', 1);
        $limit = (int)$request->getQueryParam('limit', 20);
        $start = ($page-1) * $limit;
        $orderBy = $request->getQueryParam('orderby', 'artist_id');
        $orderDir = $request->getQueryParam('orderby', 'asc');

        // get total transactions for calculating pagination
        $totalSongs = $language->songs()->count();
        $totalPages = ($totalSongs > 0) ? ceil($totalSongs / $limit) : 1; // TODO 0?

        // get paginated transactions for dispaying in the table
        $songs = $language->songs()
            ->with('artist')
            // ->with('meta')
            ->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($limit)
            ->get();

        return $this->render('console/languages/view', [
            'language' => $language,
            'songs' => $songs,

            // pagination
            'total_pages' => $totalPages,
            'page' => $page,
        ]);
    }
}
