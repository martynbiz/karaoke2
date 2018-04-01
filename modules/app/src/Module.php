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
        // //Override the default Not Found Handler
        // $container['notFoundHandler'] = function ($c) {
        //     return function ($request, $response) use ($c) {
        //
        //         $controller = new \App\Controller\HomeController($c);
        //
        //         return $controller->notFound($request, $response);
        //     };
        // };
        //
        // // view renderer
        // $container['renderer'] = function ($c) {
        //
        //     // we will add folders after instatiation so that we can assign IDs
        //     $settings = $c->get('settings')['renderer'];
        //     $folders = $settings['folders'];
        //     unset($settings['folders']);
        //
        //     $engine = \Foil\engine($settings);
        //
        //     // assign IDs
        //     foreach($folders as $id => $folder) {
        //         if (is_numeric($id)) {
        //             $engine->addFolder($folder);
        //         } else {
        //             $engine->addFolder($folder, $id);
        //         }
        //     }
        //
        //     $engine->registerFunction('translate', new \App\View\Helper\Translate($c) );
        //     $engine->registerFunction('pathFor', new \App\View\Helper\PathFor($c) );
        //     $engine->registerFunction('generateQueryString', new \App\View\Helper\GenerateQueryString($c) );
        //     $engine->registerFunction('generateSortLink', new \App\View\Helper\GenerateSortLink($c) );
        //
        //     return $engine;
        // };
        //
        // // monolog
        // $container['logger'] = function ($c) {
        //     $settings = $c->get('settings')['logger'];
        //
        //     $settings['path'] = realpath($settings['path']);
        //
        //     $logger = new Monolog\Logger($settings['name']);
        //     $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        //     $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        //     return $logger;
        // };
        //
        // // locale - required by a few services, so easier to put in container
        // $container['locale'] = function($c) {
        //     $settings = $c->get('settings')['i18n'];
        //
        //     $locale = $c['request']->getCookieParam('language', $settings['default_locale']);
        //
        //     return $locale;
        // };
        //
        // // i18n
        // $container['i18n'] = function($c) {
        //
        //     $settings = $c->get('settings')['i18n'];
        //
        //     // get the language code from the cookie, then get the language file
        //     // if no language file, or no cookie even, get default language.
        //     $locale = $c['locale'];
        //     $type = $settings['type'];
        //     $filePath = $settings['file_path'];
        //     $pattern = '/%s.php';
        //     $textDomain = 'default';
        //
        //     $translator = new \Zend\I18n\Translator\Translator();
        //     $translator->addTranslationFilePattern($type, $filePath, $pattern, $textDomain);
        //     $translator->setLocale($locale);
        //     $translator->setFallbackLocale($settings['default_locale']);
        //
        //     return $translator;
        // };
        //
        // // flash
        // $container['flash'] = function ($c) {
        //     $storage = $c['session']->get('flash_messages');
        //     if (!$storage) $c['session']->set('flash_messages', new \ArrayObject());
        //     return new \MartynBiz\FlashMessage\Flash( $storage );
        // };
        //
        // // session
        // $container['session'] = function ($c) {
        //     $settings = $c->get('settings')['session'];
        //
        //     $session_factory = new \Aura\Session\SessionFactory;
        //     $session = $session_factory->newInstance((isset($_SESSION)) ? $_SESSION : []);
        //
        //     // return session segment
        //     return $session->getSegment('__martynbiz_karaoke');
        // };
        //
        // // debugbar
        // $container['debugbar'] = function ($c) {
        //
        //     // get settings as an array
        //     $settings = [];
        //     foreach($c->get('settings') as $key => $value) {
        //         $settings[$key] = $value;
        //     }
        //
        //     $debugbar = new \MartynBiz\PHPDebugBar($settings['debugbar']);
        //
        //     $pdo = $c['model.song']->getConnection()->getPDO();
        //
        //     $debugbar->addDatabaseCollector($pdo);
        //     $debugbar->addConfigCollector( $settings ); // config array
        //
        //     return $debugbar;
        // };
        //
        // // cache
        // $container['cache'] = function ($c) {
        //
        //     $client = new \Predis\Client();
        //
        //     $adapter = new \Desarrolla2\Cache\Adapter\Predis($client);
        //     // $adapter = new \Desarrolla2\Cache\Adapter\NotCache();
        //
        //     return new \Desarrolla2\Cache\Cache($adapter);
        // };


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
