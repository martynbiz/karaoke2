<?php
namespace Tests\Functional\Console;

use Tests\Functional\BaseTestCase;

class SongsControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $response = $this->runApp('GET', '/console/songs');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.songs-list', (string)$response->getBody());
        $this->assertQueryCount('.songs-list li', 2, (string)$response->getBody());
    }

    public function testIndexWithValidQuery()
    {
        $response = $this->runApp('GET', '/console/songs?query=mile');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.songs-list', (string)$response->getBody());
        $this->assertQueryCount('.songs-list li', 1, (string)$response->getBody());
    }

    public function testIndexWithInvalidQuery()
    {
        $response = $this->runApp('GET', '/console/songs?query=idontexist');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.songs-list', (string)$response->getBody());
        $this->assertQueryCount('.songs-list li', 0, (string)$response->getBody());
    }

    public function testIndexWithLimitQuery()
    {
        $response = $this->runApp('GET', '/console/songs?limit=1');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.songs-list', (string)$response->getBody());
        $this->assertQueryCount('.songs-list li', 1, (string)$response->getBody());
    }

    public function testView()
    {
        $response = $this->runApp('GET', '/console/songs/1');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('#songs_queue_form', (string)$response->getBody());
    }
}
