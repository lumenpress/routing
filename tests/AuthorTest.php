<?php

namespace LumenPress\Routing\Tests;

class AuthorTest extends TestCase
{
    /**
     * @group author
     */
    public function testRoute()
    {
        $this->setPermalinkStructure('/%year%/%monthnum%/%day%/%postname%/');

        $app = $this->createApplication();

        $app['wp.router']->is('author', function () {
            return response('Hello World');
        });

        $response = $this->callAuthorUrl($app, 1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    /**
     * @group author
     */
    public function testRoute2()
    {
        $app = $this->createApplication();

        $app['wp.router']->is(['author' => 1], function () {
            return response('Hello World');
        });

        $response = $this->callAuthorUrl($app, 1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    /**
     * @group author
     */
    public function testRoute3()
    {
        $app = $this->createApplication();

        $app['wp.router']->is(['author' => get_userdata(1)->data->user_nicename], function () {
            return response('Hello World');
        });

        $response = $this->callAuthorUrl($app, 1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }
}
