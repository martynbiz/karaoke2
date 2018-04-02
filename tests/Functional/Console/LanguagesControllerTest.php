<?php
namespace Tests\Functional\Console;

use Tests\Functional\BaseTestCase;

class LanguagesControllerTest extends BaseTestCase
{
    public function testIndexRedirectsWhenNotLoggedIn()
    {
        $response = $this->runApp('GET', '/console/languages');

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testIndex()
    {
        $this->login($this->user);

        $response = $this->runApp('GET', '/console/languages');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.languages-list', (string)$response->getBody());
        $this->assertQueryCount('.languages-list .languages-list__item', 1, (string)$response->getBody());
    }

    public function testViewRedirectsWhenNotLoggedIn()
    {
        $response = $this->runApp('GET', '/console/languages');

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testView()
    {
        $this->login($this->user);

        $response = $this->runApp('GET', '/console/languages/1');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('.songs-list', (string)$response->getBody());
    }
}
