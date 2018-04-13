<?php
namespace Tests\Functional;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

use Symfony\Component\DomCrawler\Crawler;

use MartynBiz\Slim\Module\Auth\Model\User;

use App\Model\Song;
use App\Model\Language;
use App\Model\SongMeta;
use App\Model\ArtistMeta;
use App\Model\Artist;
use App\Model\Tag;
use App\Model\Playlist;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * Useful to have $app here so we can access during tests
     *
     * @var Slim\App
     */
    protected $app = null;

    /**
     * @var MartynBiz\Slim\Module\Auth\Model\User
     */
    protected $user = null;

    /**
     * @var App\Model\Song
     */
    protected $song = null;

    /**
     * @var App\Model\Song
     */
    protected $song2 = null;

    /**
     * @var App\Model\Artist
     */
    protected $artist = null;

    /**
     * @var App\Model\Artist
     */
    protected $artist2 = null;

    /**
     * @var App\Model\Meta
     */
    protected $meta = null;

    /**
     * @var App\Model\Language
     */
    protected $language = null;

    /**
     * @var App\Model\Tag
     */
    protected $tag = null;

    /**
     * @var App\Model\Tag
     */
    protected $tagInvalid = null;

    /**
     * We wanna also build $app so that we can gain access to container
     */
    public function setUp()
    {
        // App settings
        $appSettings = require APPLICATION_PATH . '/app/settings.php';

        // Module settings (autoload)
        $moduleSettings = [];
        foreach (array_keys($appSettings['settings']['modules']) as $dir) {
            if ($path = realpath($appSettings['settings']['folders']['modules'] . $dir . '/settings.php')) {
                $moduleSettings = array_merge_recursive($moduleSettings, require $path);
            }
        }

        // Environment settings
        $envSettings = [];
        if ($path = realpath(APPLICATION_PATH . '/app/settings-' . APPLICATION_ENV . '.php')) {
            $envSettings = require $path;
        }

        // Instantiate the app
        $settings = array_merge_recursive($moduleSettings, $appSettings, $envSettings);
        $app = new \Slim\App($settings);

        // // Register middleware
        // if ($this->withMiddleware) {
        //     require __DIR__ . '/../../app/middleware.php';
        // }
        //
        // // Register routes
        // require __DIR__ . '/../../app/routes.php';

        // initialize all modules in settings > modules > autoload [...]
        $moduleInitializer = new \MartynBiz\Slim\Module\Initializer($settings['settings']['modules']);
        $moduleInitializer->initModules($app);

        // In some cases, where services have become "frozen", we need to define
        // mocks before they are loaded

        $container = $app->getContainer();

        // session service
        $container['session'] = $this->getMockBuilder('Aura\\Session\\Segment')
            ->disableOriginalConstructor()
            ->getMock();

        // session service
        $container['martynbiz-auth.auth'] = $this->getMockBuilder('MartynBiz\\Slim\\Module\\Auth\\Auth')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app = $app;

        // fill db with test data

        $this->user = User::create([
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'name' => 'Martyn Bissett',
            'username' => 'martyn',
            'email' => 'martyn@example.com',
            'password' => 'password1',
        ]);

        $this->artist = Artist::create([
            'name' => 'Pulp',
        ]);

        $this->artist2 = Artist::create([
            'name' => 'Blur',
        ]);

        $this->language = Language::create([
            'name' => 'English',
        ]);

        $this->song = $this->artist->songs()->create([
            'name' => 'Mile end',
            'path' => '/path/to/song.mp4',
            'language_id' => $this->language->id,
        ]);

        $this->song2 = $this->artist2->songs()->create([
            'name' => 'Song 2',
            'path' => '/path/to/song2.mp4',
            'language_id' => $this->language->id,
        ]);

        $this->tag = Tag::create([
            'name' => 'Britpop',
            'is_valid' => 1,
        ]);

        $this->tagInvalid = Tag::create([
            'name' => 'Best song eva',
            'is_valid' => 0,
        ]);

        $this->meta = $this->song->meta()->create([
            'name' => 'summary',
            'value' => 'Blah blah blah',
        ]);
    }

    public function tearDown()
    {
        $container = $this->app->getContainer();
        $settings = $container->get('settings');

        // as we have foreign key constraints on meta, we cannot use
        // truncate (even if the table is empty). so we need to temporarily
        // turn off FOREIGN_KEY_CHECKS

        $connection = (new Song())->getConnection();

        // in vagrant, we have an sqlite db. we may still want to run tests there too
        // to ensure the installation is working ok. so we need to disable foreign keys
        // different from mysql
        switch($settings['eloquent']['driver']) {
            case 'sqlite':
                $connection->statement('PRAGMA foreign_keys = OFF;');
                break;
            case 'mysql':
            default:
                $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // clear tables
        SongMeta::truncate();
        Song::truncate();
        Tag::truncate();
        Language::truncate();
        ArtistMeta::truncate();
        Artist::truncate();
        Playlist::truncate();
        User::truncate();

        // turn foreign key checks back on
        switch($settings['eloquent']['driver']) {
            case 'sqlite':
                $connection->statement('PRAGMA foreign_keys = ON;');
                break;
            case 'mysql':
            default:
                $connection->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null, $headerData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (!empty($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Add header data, if it exists
        if (is_array($headerData)) {
            foreach ($headerData as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
        }

        // Set up a response object
        $response = new Response();

        // Process the application
        $response = $this->app->process($request, $response);

        // Return the response
        return $response;
    }

    public function login($user)
    {
        $container = $this->app->getContainer();

        // return an identity (eg. email)
        $container->get('martynbiz-auth.auth')
            ->method('getAttributes')
            ->willReturn( $user->toArray() );

        // by defaut, we'll make isAuthenticated return a false
        $container->get('martynbiz-auth.auth')
            ->method('isAuthenticated')
            ->willReturn(true);
    }

    /**
     * Will crawl html string for a given query (e.g. form#register)
     *
     * @param $query string
     * @param $html string
     * @return boolean
     */
    public function assertQuery($query, $html)
    {
        $crawler = new Crawler($html);
        return $this->assertEquals(1, $crawler->filter($query)->count());
    }

    /**
     * Will crawl html string for a given query (e.g. form#register)
     *
     * @param $query string
     * @param $html string
     * @return boolean
     */
    public function assertQueryCount($query, $count, $html)
    {
        $crawler = new Crawler($html);
        $this->assertEquals($count, $crawler->filter($query)->count());
    }
}
