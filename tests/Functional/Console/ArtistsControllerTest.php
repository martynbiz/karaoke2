<?php
namespace Tests\Functional\Console;

use Tests\Functional\BaseTestCase;

class ArtistsControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $this->login($this->user);

        $response = $this->runApp('GET', '/console/artists');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.artists-list', (string)$response->getBody());
        $this->assertQueryCount('.artists-list .artists-list__item', 2, (string)$response->getBody());
    }

    // public function testIndexWithValidQuery()
    // {
    //     $this->login($this->user);
    //
    //     $response = $this->runApp('GET', '/console/artists?query=pulp');
    //
    //     // assertions
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertQuery('.artists-list', (string)$response->getBody());
    //     $this->assertQueryCount('.artists-list .artists-list__item', 1, (string)$response->getBody());
    // }
    //
    // public function testIndexWithInvalidQuery()
    // {
    //     $this->login($this->user);
    //
    //     $response = $this->runApp('GET', '/console/artists?query=idontexist');
    //
    //     // assertions
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertQuery('.artists-list', (string)$response->getBody());
    //     $this->assertQueryCount('.artists-list  .artists-list__item', 0, (string)$response->getBody());
    // }
    //
    // public function testIndexWithLimitQuery()
    // {
    //     $this->login($this->user);
    //
    //     $response = $this->runApp('GET', '/console/artists?limit=1');
    //
    //     // assertions
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertQuery('.artists-list', (string)$response->getBody());
    //     $this->assertQueryCount('.artists-list .artists-list__item', 1, (string)$response->getBody());
    // }
    //
    // public function testView()
    // {
    //     $this->login($this->user);
    //
    //     $response = $this->runApp('GET', '/console/artists/1');
    //
    //     // assertions
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertQuery('.songs-list', (string)$response->getBody());
    // }
}
