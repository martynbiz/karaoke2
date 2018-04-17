<?php
namespace MartynBiz\Slim\Module\Karaoke\Controller\Console;

use MartynBiz\Slim\Module\Karaoke\Controller\BaseController;

class PlaylistsController extends BaseController
{
    /**
     * edit song form
     */
    public function listSongs($request, $response, $args)
    {
        $container = $this->getContainer();
        $playlist = $this->getCurrentPlaylist();

        $params = array_merge([
            'start' => 0,
            'limit' => 100,
            'orderby' => 'playlist_song.created_at',
            'dir' => 'asc',
        ], $request->getQueryParams());

        $songs = $playlist->songs()
            ->with('artist')
            ->with('meta')
            ->orderBy( $params['orderby'], $params['dir'] )
            ->skip( $params['start'] )
            ->take( $params['limit'] )
            ->get();

        return $this->render('console/playlists/view', [
            'songs' => $songs,
        ]);
    }

    /**
     * add song to playlist
     */
    public function addSong($request, $response, $args)
    {
        $container = $this->getContainer();

        $song = $container->get('model.song')->findOrFail((int)$request->getParam('song_id'));

        $playlist = $this->getCurrentPlaylist();

        // add this song to the playlist
        $playlist->songs()->attach($song);

        // update total_requests
        $song->increment('total_requests');

        $container->get('flash')->addMessage('success', sprintf($container->get('i18n')->translate('_song__has_been_added_to_the_playlist'), $song->name));

        return $response->withRedirect( $container->get('router')->pathFor('console_playlists_list_songs') );
    }

    /**
     * delete song from playlist
     */
    public function deleteSong($request, $response, $args)
    {
        $playlist = $this->getCurrentPlaylist();
        $song = $playlist->songs()->findOrFail( (int)$request->getParam('song_id') );

        // if get request, show confirm screen
        if (strtoupper($request->getMethod()) != 'DELETE') {
            return $this->render('console/playlists/delete_song', [
                'song' => $song,
            ]);
        }

        $container = $this->getContainer();

        // detach song from playlist
        $playlist->songs()->detach($song);

        // update total_requests
        $song->decrement('total_requests');

        $container->get('flash')->addMessage('success', sprintf($container->get('i18n')->translate('_song__has_been_removed_from_the_playlist'), $song->name));

        if ($request->getQueryParam('format') == 'json') {

            $this->renderJSON(1);

        } else {

            if ($playlist->songs()->count() > 0) {
                return $response->withRedirect( $container->get('router')->pathFor('console_playlists_list_songs') );
            } else {
                return $response->withRedirect( $container->get('router')->pathFor('console_home') );
            }

        }

    }
}
