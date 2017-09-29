<?php 

namespace LumenPress\Routing\Tests;

class RoutingTest extends TestCase
{
    public function testHomeRoute()
    {
        $this->setPermalinkStructure('/%year%/%monthnum%/%day%/%postname%/');

        $app = $this->createApplication();

        $app['wp.router']->is('home', function() {
            return response('Hello World');
        });

        $response = $this->call($app, '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testPageRoute()
    {
        $post = wp_insert_post([
            'post_title'    => 'test page route',
            'post_status'   => 'publish',
            'post_type'     => 'page',
        ]);

        $app = $this->createApplication();

        $app['wp.router']->is('page', function() {
            return response('Hello World');
        });

        $response = $this->call($app, get_permalink($post));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testSingleRoute()
    {
        $post = wp_insert_post([
            'post_title'    => 'test post route',
            'post_status'   => 'publish',
        ]);

        $app = $this->createApplication();

        $app['wp.router']->is('single', function() {
            return response('Hello World');
        });

        $response = $this->call($app, get_permalink($post));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }
}
