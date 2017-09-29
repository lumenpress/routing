<?php 

namespace LumenPress\Routing\Tests;

use Illuminate\Http\Request;
use Laravel\Lumen\Application;
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

        return $app;
    }

    public function call($app, $uri, $method = 'GET')
    {
        $this->setWpQueryVars($uri = str_replace(home_url(), '', $uri));
        return $app->handle(Request::create($uri, $method));
    }
}
