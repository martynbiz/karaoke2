<?php
namespace App;

use Slim\App;
use Slim\Container;
use MartynBiz\Slim\Module\ModuleInterface;
use MartynBiz\Slim\Module\Auth;

class Module implements ModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function initDependencies(Container $container)
    {
        // this is so we can access it during event handlers, and in the controller
        $container['playlist'] = function($c) {

            // auth user doesn't have playlist, we need app.model.user for that
            // but we'll use auth to get the user id at least
            $authUser = $c['martynbiz-auth.auth']->getCurrentUser();

            $currentUser = $c['model.user']->find( $authUser->id );

            // get the current playlist, or create a new one
            // as we're just dealing with a single session we'll just pull the first one
            if (!$playlist = $currentUser->playlists()->first()) {
                $playlist = $currentUser->playlists()->create([
                    'name' => uniqid(), // this ought to be ensured for uniqueness
                ]);
            }

            return $playlist;
        };

        // Models

        $container['model.song'] = function($c) {
            return new \App\Model\Song();
        };

        $container['model.artist'] = function($c) {
            return new \App\Model\Artist();
        };

        $container['model.language'] = function($c) {
            return new \App\Model\Language();
        };

        $container['model.tag'] = function($c) {
            return new \App\Model\Tag();
        };

        $container['model.playlist'] = function($c) {
            return new \App\Model\Playlist();
        };

        // we'll replace auth's user with app's as we wanna add custom methods
        // e.g. playlists()
        $container['model.user'] = function($c) {
            return new \App\Model\User();
        };
    }

    /**
     * {@inheritdoc}
     */
    public function initMiddleware(App $app)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function initRoutes(App $app)
    {
        $container = $app->getContainer();

        $app->group('', function() use ($app, $container) {

            $app->group('/console', function() use ($app, $container) {
                $app->get('', '\App\Controller\Console\HomeController:index')->setName('console_home');
                $app->group('/songs', function() use ($app, $container) {
                    $app->get('', '\App\Controller\Console\SongsController:index')->setName('console_songs');
                    $app->get('/most_requested', '\App\Controller\Console\SongsController:mostRequested')->setName('console_songs_most_requested');
                    $app->get('/{song_id}', '\App\Controller\Console\SongsController:view')->setName('console_songs_view');
                });
                $app->group('/artists', function() use ($app, $container) {
                    $app->get('', '\App\Controller\Console\ArtistsController:index')->setName('console_artists');
                    $app->get('/{artist_id}', '\App\Controller\Console\ArtistsController:view')->setName('console_artists_view');
                });
                $app->group('/tags', function() use ($app, $container) {
                    $app->get('', '\App\Controller\Console\TagsController:index')->setName('console_tags');
                    $app->get('/{tag_id}', '\App\Controller\Console\TagsController:view')->setName('console_tags_view');
                });
                $app->group('/languages', function() use ($app, $container) {
                    $app->get('', '\App\Controller\Console\LanguagesController:index')->setName('console_languages');
                    $app->get('/{language_id}', '\App\Controller\Console\LanguagesController:view')->setName('console_languages_view');
                });
                $app->group('/playlists', function() use ($app, $container) {
                    $app->get('/list_songs', '\App\Controller\Console\PlaylistsController:listSongs')->setName('console_playlists_list_songs');
                    $app->post('/add_song', '\App\Controller\Console\PlaylistsController:addSong')->setName('console_playlists_add_song');
                    $this->map(['GET', 'DELETE'], '/delete_song', '\App\Controller\Console\PlaylistsController:deleteSong')->setName('console_playlists_delete_song');
                });
            });

            $app->group('/player', function() use ($app, $container) {
                $app->get('', '\App\Controller\Player\PlayerController:index')->setName('player');
            });

            $app->group('/admin', function() use ($app, $container) {
                $app->group('/songs', function() use ($app, $container) {
                    $app->get('/update', '\App\Controller\Admin\SongsController:update')->setName('admin_songs_update');
                    $app->post('/update', '\App\Controller\Admin\SongsController:updatePost')->setName('admin_songs_update_post');
                });
                $app->group('/tags', function() use ($app, $container) {
                    $app->get('/edit', '\App\Controller\Admin\TagsController:edit')->setName('admin_tags_edit');
                    $app->put('/update', '\App\Controller\Admin\TagsController:update')->setName('admin_tags_update');
                });
            });

        })
        ->add( new Auth\Middleware\RememberMe($container) )
        ->add( new Auth\Middleware\RequireAuth($container) );
        // ->add(new Core\Middleware\Csrf($container));
    }
}
