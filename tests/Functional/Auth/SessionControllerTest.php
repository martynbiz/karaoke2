<?php
namespace Tests\Functional\Console;

use Tests\Functional\BaseTestCase;

class SessionControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $loginPath = $this->app->getContainer()->get('router')->pathFor('auth_session_login');
        $response = $this->runApp('GET', $loginPath);

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#login_form', (string)$response->getBody());
    }
}
