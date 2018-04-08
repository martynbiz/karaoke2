<?php
namespace Tests\Functional;

use App\Model\Song;
use App\Model\Language;
use App\Model\SongMeta;
use App\Model\ArtistMeta;
use App\Model\Artist;
use App\Model\Tag;
use App\Model\Playlist;
use App\Model\User;

use MartynBiz\Slim\Module\Auth\Traits\TestLogin;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends \MartynBiz\Slim\Module\Core\TestCase
{
    // this will give us the login function
    use TestLogin;

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
        parent::setUp();

        $app = $this->app;

        // In some cases, where services have become "frozen", we need to define
        // mocks before they are loaded

        $container = $app->getContainer();

        // session service
        $container['session'] = $this->getMockBuilder('Aura\\Session\\Segment')
            ->disableOriginalConstructor()
            ->getMock();


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
        parent::tearDown();

        $connection = (new Song())->getConnection();

        $this->forceTruncateTables($connection, function() {
            \App\Model\SongMeta::truncate();
            \App\Model\Song::truncate();
            \App\Model\Tag::truncate();
            \App\Model\Language::truncate();
            \App\Model\ArtistMeta::truncate();
            \App\Model\Artist::truncate();
            \App\Model\Playlist::truncate();
            \App\Model\User::truncate();
        });
    }
}
