<?php
namespace Tests\Functional\Console;

use Tests\Functional\BaseTestCase;

class HomeControllerTest extends BaseTestCase
{
    public function testGetIndexRedirectsWhenNotLoggedIn()
    {
        $response = $this->runApp('GET', '/console');

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testGetIndex()
    {
        $this->login($this->user);

        $response = $this->runApp('GET', '/console');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('a[href="/console/songs"].button', (string)$response->getBody());
        $this->assertQuery('a[href="/console/artists"].button', (string)$response->getBody());
        $this->assertQuery('a[href="/console/tags"].button', (string)$response->getBody());
    }
}
