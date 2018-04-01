<?php
namespace Tests\Functional\Console;

use Tests\Functional\BaseTestCase;

class TagsControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $response = $this->runApp('GET', '/console/tags');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.tags-list', (string)$response->getBody());
        $this->assertQueryCount('.tags-list .tags-list__item', 1, (string)$response->getBody());
    }

    public function testView()
    {
        $response = $this->runApp('GET', '/console/tags/1');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.songs-list', (string)$response->getBody());
    }
}
