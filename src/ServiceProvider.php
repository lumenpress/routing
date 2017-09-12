<?php

namespace LumenPress\WordPressRouter;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->singleton('wp.router', WordPressRouter::class);
        $this->app->setDispatcher($this->createDispatcher());
    }

    protected function createDispatcher()
    {
        return \FastRoute\simpleDispatcher(function ($r) {
            foreach ($this->app->getRoutes() as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        }, ['dispatcher' => GroupCountBasedDispatcher::class]);
    }
}
