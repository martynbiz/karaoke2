<?php
namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Utils;
use App\Model\Song;

class SongsController extends BaseController
{
    /**
     * Will populate the db with file name data
     */
    public function update($request, $response, $args)
    {
        return $this->render('admin/songs/update');
    }

    /**
     * Will populate the db with file name data
     */
    public function prepareSongPath($path)
    {
        $container = $this->getContainer();
        $parentDir = $container->get('settings')['song_files']['parent_dir'];

        return str_replace($parentDir, '', $path);
    }

    /**
     * Will populate the db with file name data
     */
    public function updatePost($request, $response, $args)
    {
        $container = $this->getContainer();
        $settings = $container->get('settings')['song_files'];

        // Get paths as array
        $paths = $this->scanDir( $settings['parent_dir'] );
        // $paths = array_slice($paths, 0, 1);// TODO remove this
        foreach ($paths as $i => $path) {

            // e.g. /var/www/karaoke/../Lang/Artist/Song.mp4 -> /Lang/Artist/Song.mp4
            $paths[$i] = $this->prepareSongPath($paths[$i]);
        }

        // remove songs that are not in the latest scan
        $container->get('model.song')
            ->whereNotIn('path', $paths)
            ->delete();

        // remove artists that no longer have any songs (as they may have just
        // been deleted in the above)
        $artists = $container->get('model.artist')
            ->with('songs')
            ->get();
        foreach ($artists as $artist) {
            if (!count($artist->songs)) {
                $artist->delete();
            }
        }

        $parentDir = $settings['parent_dir'];
        foreach ($paths as $path) {

            // extract the artist/name from path
            preg_match($settings['path_pattern'], $path, $matches);
            list($match, $languageName, $artistName, $songTitle) = $matches;

            if ($artistName and $songTitle) {

                // find or create artist
                try {

                    $artist = $container->get('model.artist')
                        ->where('name', $artistName)
                        ->first();
                    if (!$artist) {
                        $artist = $container->get('model.artist')->create([
                            'name' => $artistName,
                        ]);
                    }

                    $language = $container->get('model.language')
                        ->where('name', $languageName)
                        ->first();
                    if (!$language) {
                        $language = $container->get('model.language')->create([
                            'name' => $languageName,
                        ]);
                    }

                    $song = $artist->songs()
                        ->where('name', $songTitle) // TODO use path not name? (might have been edited)
                        ->first();
                    if (!$song) {
                        $song = $artist->songs()->create([
                            'name' => $songTitle,
                            'language_id' => $language->id,
                            'path' => $path,
                        ]);
                    }

                } catch (Exception $e) {
                    $container->get('flash_messages')->addMessage('error', $e->getMessages());
                }

            }

        }

        // fetch meta data from api
        $this->fetchMetaData($logs);

        $container->get('flash')->addMessage('success', $container->get('i18n')->translate('songs_have_been_updated'));

        return $response->withRedirect( $container->get('router')->pathFor('admin_songs_update') );
    }

    /**
     * @param $song {Song}
     * @param $sleepBetweenCalls {integer} How many seconds to wait between api calls
     */
    protected function fetchMetaData(&$logs) {

        $container = $this->getContainer();
        $settings = $container->get('settings')['song_files'];

        // get song information from api
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://ws.audioscrobbler.com/2.0/'
        ]);
        $apiKey = $settings['api_key']; //'73a4c2716ad6250f92d0210140e47a0c';

        $songs = $container->get('model.song')
            ->where('has_meta', 0)
            ->get();

        // get song meta
        foreach ($songs as $song) {

            $artist = $song->artist;

            $url = '?api_key='.$apiKey.'&format=json&method=track.getInfo&artist='.$song->artist->name.'&track='.$song->name;
            $res = $client->request('GET', $url);
            $decoded = json_decode($res->getBody());

            // TODO song_meta.summary
            if (isset($decoded->track->wiki) and isset($decoded->track->wiki->summary)) {
                $song->meta()->create([
                    'name' => 'summary',
                    'value' => $decoded->track->wiki->summary,
                ]);
            }

            // attach tags
            if (isset($decoded->track->toptags)) {

                // // detach current tags
                // $song->tags()->detach();

                $tagIdsToSync = [];
                foreach ($decoded->track->toptags->tag as $tagObj) {

                    $tagName = ucfirst($tagObj->name);

                    // just so we don't get tags like "Adele", not entirely fool proof though
                    if (strtolower($tagName) != strtolower($artist->name)) {

                        $tag = $container->get('model.tag')
                            ->where('name', $tagName)
                            ->first();
                        if (!$tag) {
                            $tag = $song->tags()->create([
                                'name' => $tagName,
                            ]);
                        }

                        array_push($tagIdsToSync, $tag->id);

                    }
                }

                $song->tags()->sync($tagIdsToSync);
            }

            $song->update([
                'has_meta' => 1,
            ]);

            sleep(1); // so we don't throttle the api

        }

        $artists = $container->get('model.artist')
            ->where('has_meta', 0)
            ->get();

        // get song meta
        foreach ($artists as $artist) {

            $url = '?api_key='.$apiKey.'&format=json&method=artist.getInfo&artist='.$song->artist->name;
            $res = $client->request('GET', $url);
            $decoded = json_decode($res->getBody());

            // artist_meta.summary
            if (isset($decoded->artist->bio) and isset($decoded->artist->bio->summary)) {
                $artist->meta()->create([
                    'name' => 'summary',
                    'value' => $decoded->artist->bio->summary,
                ]);
            }

            // attach tags
            $tagIdsToSync = [];
            if (isset($decoded->artist->tags->tag)) {

                // detach current tags
                $artist->tags()->detach();

                foreach ($decoded->artist->tags->tag as $tagObj) {

                    $tagName = ucfirst($tagObj->name);

                    // just so we don't get tags like "Adele", not entirely fool proof though
                    if (strtolower($tagName) != strtolower($artist->name)) {

                        $tag = $container->get('model.tag')
                            ->where('name', $tagName)
                            ->first();
                        if (!$tag) {
                            $tag = $artist->tags()->create([
                                'name' => $tagName,
                            ]);
                        }

                        array_push($tagIdsToSync, $tag->id);

                    }
                }

                $artist->tags()->sync($tagIdsToSync);
            }

            $artist->update([
                'has_meta' => 1,
            ]);

            sleep(1); // so we don't throttle the api

        }

    }

    protected function scanDir($path, &$songs = []) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                array_push($songs, $file);
            } else {
                $this->scanDir($file, $songs);
            }
        }
        return $songs;
    }
}
