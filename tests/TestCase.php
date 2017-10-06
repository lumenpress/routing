<?php

namespace LumenPress\Routing\Tests;

use Illuminate\Http\Request;
use LumenPress\Lumen\Application;
use LumenPress\Routing\ServiceProvider;
use LumenPress\Testing\WordPressTestCase;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    use WordPressTestCase;

    protected function createApplication()
    {
        $app = new Application;
        $app->withFacades();
        $app->withEloquent();
        $app->register(ServiceProvider::class);

        if (! property_exists($app, 'router')) {
            $app->router = $app;
        }

        return $app;
    }

    public function call($app, $uri, $method = 'GET')
    {
        $this->setWpQueryVars($uri = str_replace(home_url(), '', $uri));

        return $app->handle(Request::create($uri, $method));
    }

    public function callPostUrl($app, $post, $method = 'GET')
    {
        return $this->call($app, get_permalink($post), $method);
    }

    public function callTaxonomyUrl($app, $id, $taxonomy, $method = 'GET')
    {
        return $this->call($app, get_term_link($id, $taxonomy), $method);
    }

    public function callAuthorUrl($app, $authorId, $method = 'GET')
    {
        return $this->call($app, get_author_posts_url($authorId), $method);
    }

    public static function assertResponse($response, $content, $status = 200)
    {
        self::assertEquals($status, $response->getStatusCode());
        self::assertEquals($content, $response->getContent());
    }
}
