<?php

namespace LumenPress\WordPressRouter;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('wp.router.conditions', function ($app) {
            return config('wp/route-conditions');
        });

        $this->app->singleton('wp.router', function ($app) {
            return new Router($app);
        });

        $this->app->setDispatcher($this->createDispatcher());
    }

    public function isLumen($version = null)
    {
        return preg_match('/^Lumen \('.str_replace('.', '\.', $version).'/i', $this->app->version());
    }

    protected function createDispatcher()
    {
        return \FastRoute\simpleDispatcher(function ($r) {
            $routes = property_exists($this->app, 'router') ? $this->app->router->getRoutes() : $this->app->getRoutes();

            foreach ($routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        }, ['dispatcher' => GroupCountBasedDispatcher::class]);
    }
}
