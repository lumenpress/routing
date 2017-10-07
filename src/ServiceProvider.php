<?php

namespace LumenPress\Routing;

use LumenPress\Routing\Laravel\Router as LaravelRouter;
use LumenPress\Routing\Lumen\GroupCountBasedDispatcher;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('wp.router', function () {
            return new Router($this->app);
        });

        if ($this->isLumen()) {
            add_action('init', function () {
                $this->app->setDispatcher($this->createDispatcher());
            });
        } else {
            $this->app->singleton('router', function ($app) {
                return new LaravelRouter($app['events'], $app);
            });
        }
    }

    public function isLumen($version = null)
    {
        return stripos($this->app->version(), 'Lumen') !== false;
    }

    protected function createDispatcher()
    {
        return \FastRoute\simpleDispatcher(function ($r) {
            $routes = property_exists($this->app, 'router') 
                ? $this->app->router->getRoutes() 
                : $this->app->getRoutes();

            foreach ($routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        }, ['dispatcher' => GroupCountBasedDispatcher::class]);
    }
}
